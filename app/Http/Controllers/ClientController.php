<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients
     */
    public function index(Request $request)
    {
        $query = Client::where('company_id', Auth::user()->company_id);

        // Filtrer par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Trier
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $clients = $query->paginate($request->get('per_page', 15));

        return view('clients.index', compact('clients'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'vat_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,lead',
            'notes' => 'nullable|string',
        ]);

        $client = Client::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'website' => $request->website,
            'contact_person' => $request->contact_person,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'tax_number' => $request->tax_number,
            'vat_number' => $request->vat_number,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Afficher les détails d'un client
     */
    public function show(Client $client)
    {
        $this->checkCompanyAccess($client);

        // Récupérer les statistiques du client
        $stats = [
            'total_projects' => $client->projects()->count(),
            'total_invoices' => $client->invoices()->count(),
            'total_payments' => $client->payments()->sum('amount'),
            'total_due' => $client->invoices()->where('status', 'pending')->sum('balance'),
        ];

        return view('clients.show', compact('client', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Client $client)
    {
        $this->checkCompanyAccess($client);

        return view('clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $this->checkCompanyAccess($client);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'vat_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,lead',
            'notes' => 'nullable|string',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Supprimer un client (soft delete)
     */
    public function destroy(Client $client)
    {
        $this->checkCompanyAccess($client);

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }

    /**
     * Restaurer un client supprimé
     */
    public function restore($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($client);

        $client->restore();

        return redirect()->route('clients.index')
            ->with('success', 'Client restauré avec succès.');
    }

    /**
     * Supprimer définitivement un client
     */
    public function forceDelete($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $this->checkCompanyAccess($client);

        $client->forceDelete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé définitivement.');
    }

    /**
     * Exporter les clients en CSV
     */
    public function export()
    {
        $clients = Client::where('company_id', Auth::user()->company_id)->get();

        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // En-têtes CSV
        fputcsv($handle, ['Code', 'Nom', 'Email', 'Téléphone', 'Contact', 'Ville', 'Pays', 'Statut']);

        // Données
        foreach ($clients as $client) {
            fputcsv($handle, [
                $client->code,
                $client->name,
                $client->email,
                $client->phone,
                $client->contact_person,
                $client->city,
                $client->country,
                $client->status == 'active' ? 'Actif' : ($client->status == 'inactive' ? 'Inactif' : 'Prospect'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Vérifier que le client appartient à l'entreprise
     */
    private function checkCompanyAccess(Client $client)
    {
        if ($client->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
