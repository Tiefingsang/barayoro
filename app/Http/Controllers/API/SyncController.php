<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Envoyer les modifications locales au serveur
     */
    public function push(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|string',
            'operations' => 'required|array',
        ]);

        $companyId = Auth::user()->company_id;
        $results = [];

        DB::beginTransaction();

        try {
            foreach ($request->operations as $operation) {
                $result = $this->processOperation(
                    $request->entity_type,
                    $operation,
                    $companyId
                );
                $results[] = $result;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Traiter une opération
     */
    private function processOperation($entityType, $operation, $companyId)
    {
        $model = $this->getModelClass($entityType);

        switch ($operation['operation']) {
            case 'create':
                $data = $operation['data'];
                $data['company_id'] = $companyId;
                $data['sync_status'] = 'synced';

                $entity = $model::create($data);

                return [
                    'uuid' => $operation['uuid'],
                    'success' => true,
                    'id' => $entity->id
                ];

            case 'update':
                $entity = $model::where('uuid', $operation['uuid'])
                                ->where('company_id', $companyId)
                                ->first();

                if ($entity) {
                    $entity->update($operation['data']);
                    $entity->sync_status = 'synced';
                    $entity->save();

                    return [
                        'uuid' => $operation['uuid'],
                        'success' => true
                    ];
                }

                return [
                    'uuid' => $operation['uuid'],
                    'success' => false,
                    'error' => 'Entity not found'
                ];

            case 'delete':
                $entity = $model::where('uuid', $operation['uuid'])
                                ->where('company_id', $companyId)
                                ->first();

                if ($entity) {
                    $entity->delete();

                    return [
                        'uuid' => $operation['uuid'],
                        'success' => true
                    ];
                }

                return [
                    'uuid' => $operation['uuid'],
                    'success' => false,
                    'error' => 'Entity not found'
                ];

            default:
                return [
                    'uuid' => $operation['uuid'],
                    'success' => false,
                    'error' => 'Unknown operation'
                ];
        }
    }

    /**
     * Récupérer les modifications distantes
     */
    public function pull(Request $request)
    {
        $request->validate([
            'last_sync' => 'nullable|date'
        ]);

        $companyId = Auth::user()->company_id;
        $lastSync = $request->last_sync;

        $changes = [];

        // Récupérer les changements pour chaque type d'entité
        $entityTypes = ['tasks', 'projects', 'clients', 'products', 'invoices'];

        foreach ($entityTypes as $entityType) {
            $model = $this->getModelClass($entityType);

            $query = $model::where('company_id', $companyId);

            if ($lastSync) {
                $query->where(function($q) use ($lastSync) {
                    $q->where('updated_at', '>', $lastSync)
                      ->orWhere('deleted_at', '>', $lastSync);
                });
            }

            $entities = $query->get();

            if ($entities->count() > 0) {
                $changes[$entityType] = $entities->map(function($entity) {
                    return [
                        'uuid' => $entity->uuid,
                        'data' => $entity->toArray(),
                        'updated_at' => $entity->updated_at,
                        'deleted_at' => $entity->deleted_at
                    ];
                });
            }
        }

        return response()->json([
            'success' => true,
            'changes' => $changes,
            'sync_time' => now()->toISOString()
        ]);
    }

    /**
     * Résoudre un conflit
     */
    public function resolveConflict(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|string',
            'entity_uuid' => 'required|string',
            'resolution' => 'required|in:local,remote'
        ]);

        $companyId = Auth::user()->company_id;
        $model = $this->getModelClass($request->entity_type);

        if ($request->resolution === 'remote') {
            // La version distante gagne
            $entity = $model::where('uuid', $request->entity_uuid)
                            ->where('company_id', $companyId)
                            ->first();

            if ($entity) {
                return response()->json([
                    'success' => true,
                    'entity' => $entity
                ]);
            }
        }

        // La version locale gagne - rien à faire
        return response()->json([
            'success' => true,
            'message' => 'Local version kept'
        ]);
    }

    /**
     * Statut de synchronisation
     */
    public function status()
    {
        $companyId = Auth::user()->company_id;

        // Compter les entités avec sync_status 'pending'
        $pendingCount = [
            'tasks' => Task::where('company_id', $companyId)
                           ->where('sync_status', 'pending')
                           ->count(),
            'projects' => Project::where('company_id', $companyId)
                                 ->where('sync_status', 'pending')
                                 ->count(),
            'clients' => Client::where('company_id', $companyId)
                               ->where('sync_status', 'pending')
                               ->count(),
        ];

        return response()->json([
            'success' => true,
            'pending_count' => array_sum($pendingCount),
            'details' => $pendingCount
        ]);
    }

    /**
     * Obtenir la classe du modèle
     */
    private function getModelClass($entityType)
    {
        $models = [
            'tasks' => \App\Models\Task::class,
            'projects' => \App\Models\Project::class,
            'clients' => \App\Models\Client::class,
            'products' => \App\Models\Product::class,
            'invoices' => \App\Models\Invoice::class,
            'users' => \App\Models\User::class,
        ];

        return $models[$entityType] ?? null;
    }
}
