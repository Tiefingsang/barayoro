<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Client;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Str;
use App\Models\User;  // ← AJOUTEZ CETTE LIGNE

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('company_id', Auth::user()->company_id)
            ->with(['client', 'creator']);

        // Filtres
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('client', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $statusCounts = [
            'total' => Order::where('company_id', Auth::user()->company_id)->count(),
            'pending' => Order::where('company_id', Auth::user()->company_id)->where('status', 'pending')->count(),
            'processing' => Order::where('company_id', Auth::user()->company_id)->where('status', 'processing')->count(),
            'delivered' => Order::where('company_id', Auth::user()->company_id)->where('status', 'delivered')->count(),
            'cancelled' => Order::where('company_id', Auth::user()->company_id)->where('status', 'cancelled')->count(),
        ];

        return view('orders.index', compact('orders', 'statusCounts'));
    }

    public function create()
    {
        $clients = Client::where('company_id', Auth::user()->company_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $products = Product::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->where('type', 'product')
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('clients', 'products'));
    }

public function store(Request $request)
{
    // 🔥 CORRECTION: Nettoyer la requête si client existant
    if ($request->client_type === 'existing') {
        $request->request->remove('new_client_name');
        $request->request->remove('new_client_email');
        $request->request->remove('new_client_phone');
        $request->request->remove('new_client_address');
    }

    $request->validate([
        'client_id' => 'nullable|exists:clients,id',
        'client_type' => 'required|in:existing,new',
        'new_client_name' => 'required_if:client_type,new|string|max:255',
        'new_client_email' => 'nullable|email|max:255',
        'new_client_phone' => 'nullable|string|max:20',
        'new_client_address' => 'nullable|string',
        'type' => 'required|in:estimate,order',
        'order_date' => 'required|date',
        'estimated_delivery_date' => 'nullable|date|after_or_equal:order_date',
        'shipping_cost' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
        'items.*.discount_amount' => 'nullable|numeric|min:0',
    ]);

    try {
        DB::beginTransaction();

        // Gestion du client
        $clientId = $request->client_id;

        if ($request->client_type === 'new') {
            // Validation supplémentaire pour le nouveau client
            if (empty($request->new_client_name)) {
                throw new \Exception('Le nom du client est requis.');
            }

            // Créer un nouveau client
            $client = Client::create([
                'uuid' => Str::uuid(),
                'company_id' => Auth::user()->company_id,
                'name' => $request->new_client_name,
                'email' => $request->new_client_email,
                'phone' => $request->new_client_phone,
                'address' => $request->new_client_address,
                'status' => 'active',
            ]);
            $clientId = $client->id;

            // Notification admin pour nouveau client
            try {
                $admins = User::where('company_id', Auth::user()->company_id)
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'admin');
                    })
                    ->get();

                if ($admins->isNotEmpty()) {
                    NotificationHelper::send(
                        $admins,
                        'Nouveau client enregistré',
                        'Un nouveau client a été créé via une commande: ' . $client->name,
                        'customer',
                        route('clients.show', $client),
                        ['client_id' => $client->id]
                    );
                }
            } catch (\Exception $e) {
                \Log::warning('Erreur notification: ' . $e->getMessage());
            }
        } else {
            // Mode client existant - vérifier que client_id est fourni
            if (empty($clientId)) {
                throw new \Exception('Veuillez sélectionner un client.');
            }
        }

        // Générer le numéro de commande
        $orderNumber = $this->generateOrderNumber();

        // Créer la commande
        $order = Order::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'client_id' => $clientId,
            'order_number' => $orderNumber,
            'type' => $request->type,
            'status' => $request->type === 'estimate' ? 'draft' : 'confirmed',
            'order_date' => $request->order_date,
            'estimated_delivery_date' => $request->estimated_delivery_date,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // Ajouter les articles
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $taxRate = $item['tax_rate'] ?? $product->tax_rate ?? 0;
            $discount = $item['discount_amount'] ?? 0;
            $subtotal = $item['quantity'] * $item['unit_price'];
            $taxAmount = ($subtotal - $discount) * ($taxRate / 100);
            $total = $subtotal - $discount + $taxAmount;

            OrderItem::create([
                'uuid' => Str::uuid(),
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'product_sku' => $product->sku ?? null,
                'product_description' => $product->description ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discount,
                'subtotal' => $subtotal,
                'total' => $total,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        // Recalculer les totaux
        $order->load('items');

        $subtotal = $order->items->sum('subtotal');
        $taxAmount = $order->items->sum('tax_amount');
        $discountAmount = $order->items->sum('discount_amount');
        $total = $order->items->sum('total') + ($request->shipping_cost ?? 0);

        $order->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total' => $total,
        ]);

        // Générer automatiquement la facture si c'est une commande
        if ($request->type === 'order') {
            $invoice = $this->generateInvoiceFromOrder($order);

            DB::commit();

            $message = 'Commande créée avec succès.';
            if ($invoice) {
                $message .= ' Facture #' . $invoice->invoice_number . ' générée automatiquement.';
            }

            return redirect()->route('orders.show', $order)
                ->with('success', $message);
        }

        DB::commit();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Devis créé avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Générer automatiquement une facture à partir d'une commande
 */
/**
 * Générer automatiquement une facture à partir d'une commande
 */
/**
 * Générer automatiquement une facture à partir d'une commande
 */
private function generateInvoiceFromOrder(Order $order)
{
    try {
        $invoiceNumber = $this->generateInvoiceNumber();

        // Calculer le subtotal
        $subtotal = $order->items->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });

        $invoice = Invoice::create([
            'uuid' => Str::uuid(),
            'company_id' => $order->company_id,
            'client_id' => $order->client_id,
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'type' => 'invoice',
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $subtotal,
            'tax' => $order->tax_amount,
            'discount' => $order->discount_amount,
            'total' => $order->total,
            'paid' => 0,
            'balance' => $order->total,
            'notes' => 'Facture générée depuis la commande #' . $order->order_number,
            'created_by' => Auth::id(),
        ]);

        // Créer les lignes de facture
        foreach ($order->items as $item) {
            $itemSubtotal = $item->quantity * $item->unit_price;

            \DB::table('invoice_items')->insert([
                'uuid' => (string) Str::uuid(),
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $itemSubtotal,
                'discount_amount' => $item->discount_amount ?? 0,
                'tax_rate' => $item->tax_rate,
                'tax_amount' => $item->tax_amount,
                'total' => $item->total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mettre à jour les items JSON
        $itemsArray = [];
        foreach ($order->items as $item) {
            $itemsArray[] = [
                'product_id' => $item->product_id,
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount_amount ?? 0,
                'tax_rate' => $item->tax_rate,
                'tax_amount' => $item->tax_amount,
                'total' => $item->total,
            ];
        }
        $invoice->update(['items' => $itemsArray]);

        // Lier la facture à la commande
        $order->update(['invoice_id' => $invoice->id]);

        // ⚡ CORRECTION ICI : Vérifier que $invoice est bien un modèle, pas une collection
        if ($invoice && $invoice->id) {
            try {
                NotificationHelper::newInvoice($invoice);
                \Log::info('Notification facture envoyée pour ID: ' . $invoice->id);
            } catch (\Exception $e) {
                \Log::error('Erreur notification facture: ' . $e->getMessage());
            }
        }

        return $invoice;

    } catch (\Exception $e) {
        \Log::error('Erreur génération facture: ' . $e->getMessage());
        throw $e;
    }
}

    public function show(Order $order)
    {
        $this->checkCompanyAccess($order);

        $order->load(['client', 'items.product', 'histories.user', 'creator', 'invoice']);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->checkCompanyAccess($order);

        if (!$order->canBeModified()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $clients = Client::where('company_id', Auth::user()->company_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $products = Product::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $order->load('items');

        return view('orders.edit', compact('order', 'clients', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $this->checkCompanyAccess($order);

        if (!$order->canBeModified()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'estimated_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $order->update([
                'client_id' => $request->client_id,
                'order_date' => $request->order_date,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Commande mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        $this->checkCompanyAccess($order);

        if (!$order->canBeModified()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut pas être supprimée.');
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->checkCompanyAccess($order);

        $request->validate([
            'status' => 'required|in:draft,pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $order->updateStatus($request->status, $request->notes);

        // Si la commande est livrée, mettre à jour le stock
        if ($request->status === 'delivered' && $oldStatus !== 'delivered') {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock_quantity -= $item->quantity;
                    $product->save();
                }
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Statut de la commande mis à jour.');
    }

public function generateInvoice(Order $order)
{
    $this->checkCompanyAccess($order);

    if ($order->invoice_id) {
        return redirect()->route('invoices.show', $order->invoice_id)
            ->with('info', 'Une facture existe déjà pour cette commande.');
    }

    try {
        DB::beginTransaction();

        // Créer la facture
        $invoice = Invoice::create([
            'uuid' => Str::uuid(),
            'company_id' => $order->company_id,
            'client_id' => $order->client_id,
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'type' => 'invoice',
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'invoice_date' => now(),
            'subtotal' => $order->subtotal,
            'tax_amount' => $order->tax_amount,
            'discount_amount' => $order->discount_amount,
            'total' => $order->total,
            'notes' => 'Facture générée depuis la commande #' . $order->order_number,
        ]);

        // Créer les lignes de facture - CORRIGÉ
        foreach ($order->items as $item) {
            $invoice->items()->create([
                'uuid' => Str::uuid(),
                'product_id' => $item->product_id,
                'description' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price,  // ← AJOUTÉ
                'tax_rate' => $item->tax_rate,
                'tax_amount' => $item->tax_amount,
                'discount_amount' => $item->discount_amount,
                'total' => $item->total,
            ]);
        }

        // Lier la facture à la commande
        $order->update(['invoice_id' => $invoice->id]);
        $order->addHistory($order->status, 'Facture générée #' . $invoice->invoice_number);

        DB::commit();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture générée avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Erreur lors de la génération de la facture: ' . $e->getMessage());
    }
}

    public function export(Request $request)
    {
        $orders = Order::where('company_id', Auth::user()->company_id)
            ->with(['client'])
            ->get();

        $filename = 'commandes_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // En-têtes
        fputcsv($handle, [
            'N° Commande', 'Client', 'Date', 'Statut', 'Statut Paiement',
            'Sous-total', 'TVA', 'Total', 'Date livraison', 'Tracking'
        ]);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->client->name,
                $order->order_date->format('d/m/Y'),
                $order->getStatusLabelAttribute(),
                $order->getPaymentStatusLabelAttribute(),
                number_format($order->subtotal, 2, ',', ' '),
                number_format($order->tax_amount, 2, ',', ' '),
                number_format($order->total, 2, ',', ' '),
                $order->delivery_date ? $order->delivery_date->format('d/m/Y') : '-',
                $order->tracking_number ?? '-',
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

     private function checkCompanyAccess(Order $order)
    {
        if ($order->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Générer un numéro de commande unique
     */
    private function generateOrderNumber()
    {
        $prefix = 'CMD-' . date('Ymd') . '-';
        $lastOrder = Order::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -6));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Générer un numéro de facture unique
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'FACT-' . date('Ymd') . '-';
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -6));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
