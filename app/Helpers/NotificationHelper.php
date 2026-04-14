<?php
// app/Helpers/NotificationHelper.php
namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationHelper
{
    /**
     * Envoyer une notification à un ou plusieurs utilisateurs
     */
  public static function send($users, $title, $message, $type = 'info', $actionUrl = null, $data = [])
{
   if ($users instanceof \Illuminate\Support\Collection) {
    $users = $users->all(); // transforme en array de User
} elseif (!is_array($users)) {
    $users = [$users];
}

    $notifications = [];
    foreach ($users as $user) {
        // 🔍 DÉBOGAGE
        \Log::info('send: type de user', [
            'type' => gettype($user),
            'is_collection' => $user instanceof \Illuminate\Support\Collection,
            'is_user' => $user instanceof User,
            'user_id' => is_object($user) ? ($user->id ?? 'no_id') : 'not_object'
        ]);

        if ($user instanceof User) {
            $userId = $user->id;
            $companyId = $user->company_id;
        } else if ($user instanceof \Illuminate\Support\Collection) {
            // Si c'est une collection, on prend le premier élément
            \Log::warning('send: user est une collection, extraction du premier élément');
            $firstUser = $user->first();
            if ($firstUser && $firstUser instanceof User) {
                $userId = $firstUser->id;
                $companyId = $firstUser->company_id;
            } else {
                continue;
            }
        } else {
            $userId = $user;
            $userModel = User::find($userId);
            $companyId = $userModel ? $userModel->company_id : null;
        }

        if ($companyId) {
            $notifications[] = [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'company_id' => $companyId,
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'data' => json_encode($data),
                'is_read' => false,
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    if (!empty($notifications)) {
        Notification::insert($notifications);
    }

    return $notifications;
}

    /**
     * Notifier tous les admins d'une entreprise
     */
    public static function notifyAdmins($companyId, $title, $message, $type = 'info', $actionUrl = null, $data = [])
    {
        $admins = User::where('company_id', $companyId)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->get();

        return self::send($admins, $title, $message, $type, $actionUrl, $data);
    }

    /**
     * Notifier un département spécifique
     */
    public static function notifyDepartment($departmentId, $title, $message, $type = 'info', $actionUrl = null, $data = [])
    {
        $users = User::where('department_id', $departmentId)->get();
        return self::send($users, $title, $message, $type, $actionUrl, $data);
    }

    /**
     * Notification de nouvelle commande
     */
 public static function newOrder($order)
{
    \Log::info('=== NOTIFICATION newOrder ===');
    \Log::info('Order ID: ' . $order->id);
    \Log::info('Order Number: ' . $order->order_number);
    \Log::info('Company ID: ' . $order->company_id);

    // Notifier l'admin
    $result = self::notifyAdmins($order->company_id,
        'Nouvelle commande #' . $order->order_number,
        'Une nouvelle commande a été créée par ' . ($order->creator->name ?? 'client'),
        'order',
        route('orders.show', $order),
        ['order_id' => $order->id, 'order_number' => $order->order_number]
    );

    // ⚡ CORRECTION ICI
    try {
        // Charger la relation client
        if (!$order->relationLoaded('client')) {
            $order->load('client');
        }

        $client = $order->client;

        if ($client && $client->email) {
            self::send($client->user_id ?? null,
                'Confirmation de commande',
                'Votre commande #' . $order->order_number . ' a été enregistrée',
                'customer',
                route('orders.show', $order),
                ['order_id' => $order->id]
            );
        }
    } catch (\Exception $e) {
        \Log::warning('Erreur envoi notification client commande: ' . $e->getMessage());
    }

    \Log::info('Nombre de notifications créées: ' . count($result));
    \Log::info('=== FIN NOTIFICATION ===');
}

    /**
     * Notification de changement de statut de commande
     */
    public static function orderStatusChanged($order, $oldStatus, $newStatus)
    {
        $statusMessages = [
            'confirmed' => 'a été confirmée',
            'processing' => 'est en cours de traitement',
            'shipped' => 'a été expédiée',
            'delivered' => 'a été livrée',
            'cancelled' => 'a été annulée',
        ];

        $message = "La commande #{$order->order_number} " . ($statusMessages[$newStatus] ?? 'a changé de statut');

        self::notifyAdmins($order->company_id, 'Statut commande mis à jour', $message, 'order_status', route('orders.show', $order), ['order_id' => $order->id, 'old_status' => $oldStatus, 'new_status' => $newStatus]);

        if ($order->client && $order->client->email) {
            self::send($order->client->user_id ?? null, 'Mise à jour de votre commande', $message, 'customer', route('orders.show', $order));
        }
    }

    /**
     * Notification de nouvelle facture
     */
    /**
     * Notification de nouvelle facture
     */
public static function newInvoice($invoice)
{
    // Vérification renforcée
    if (!$invoice || !is_object($invoice)) {
        \Log::error('newInvoice: Paramètre invalide', [
            'type' => gettype($invoice),
            'value' => $invoice
        ]);
        return [];
    }

    // Vérification spécifique pour les collections
    if ($invoice instanceof \Illuminate\Support\Collection) {
        \Log::error('newInvoice: Reçu une collection au lieu d\'un modèle', [
            'count' => $invoice->count(),
            'items' => $invoice->toArray()
        ]);

        if ($invoice->isNotEmpty()) {
            $invoice = $invoice->first();
            \Log::info('newInvoice: Utilisation du premier élément de la collection', [
                'invoice_id' => $invoice->id ?? 'unknown'
            ]);
        } else {
            return [];
        }
    }

    if (!isset($invoice->company_id)) {
        \Log::error('newInvoice: Pas de company_id', [
            'class' => get_class($invoice),
            'attributes' => $invoice->toArray() ?? []
        ]);
        return [];
    }

    \Log::info('newInvoice appelée pour facture ID: ' . $invoice->id . ', Company ID: ' . $invoice->company_id);

    $result = self::notifyAdmins($invoice->company_id,
        'Nouvelle facture #' . $invoice->invoice_number,
        'Une nouvelle facture a été générée pour un montant de ' . number_format($invoice->total, 0) . ' FCFA',
        'invoice',
        route('invoices.show', $invoice),
        ['invoice_id' => $invoice->id, 'total' => $invoice->total]
    );

    // ⚡ CORRECTION ICI : client est une relation, il faut charger le client
    try {
        // Charger la relation client si elle n'est pas déjà chargée
        if (!$invoice->relationLoaded('client')) {
            $invoice->load('client');
        }

        $client = $invoice->client;

        if ($client && $client->email) {
            self::send($client->user_id ?? null,
                'Votre facture est disponible',
                'Facture #' . $invoice->invoice_number . ' d\'un montant de ' . number_format($invoice->total, 0) . ' FCFA',
                'customer',
                route('invoices.show', $invoice)
            );
        }
    } catch (\Exception $e) {
        \Log::warning('Erreur envoi notification client: ' . $e->getMessage());
    }

    return $result;
}

    /**
     * Notification de paiement reçu
     */
    public static function paymentReceived($payment)
    {
        $invoice = $payment->invoice;

        self::notifyAdmins($payment->company_id, 'Paiement reçu', 'Un paiement de ' . number_format($payment->amount, 0) . ' FCFA a été reçu pour la facture #' . $invoice->invoice_number, 'payment', route('invoices.show', $invoice), ['payment_id' => $payment->id, 'amount' => $payment->amount]);
    }

    /**
     * Notification de stock faible
     */
    public static function lowStock($product)
    {
        self::notifyAdmins($product->company_id, 'Stock faible - ' . $product->name, "Le produit {$product->name} n'a plus que {$product->stock_quantity} unités en stock (seuil: {$product->min_stock})", 'warning', route('products.edit', $product), ['product_id' => $product->id, 'stock' => $product->stock_quantity]);
    }

    /**
     * Notification de nouveau projet
     */
    public static function newProject($project)
    {
        self::notifyAdmins($project->company_id, 'Nouveau projet - ' . $project->name, 'Un nouveau projet a été créé', 'project', route('projects.show', $project), ['project_id' => $project->id]);
    }

    /**
     * Notification de tâche assignée
     */
    public static function taskAssigned($task)
    {
        if ($task->assigned_to) {
            self::send($task->assigned_to, 'Nouvelle tâche assignée', 'Vous avez été assigné à la tâche: ' . $task->title, 'task', route('tasks.show', $task), ['task_id' => $task->id]);
        }
    }
}
