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
use Illuminate\Support\Str;

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
        $request->validate([
            'client_id' => 'required|exists:clients,id',
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

            $order = Order::create([
                'uuid' => Str::uuid(),
                'company_id' => Auth::user()->company_id,
                'client_id' => $request->client_id,
                'type' => $request->type,
                'status' => $request->type === 'estimate' ? 'draft' : 'pending',
                'order_date' => $request->order_date,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'notes' => $request->notes,
                'shipping_address_line1' => $request->shipping_address_line1,
                'shipping_address_line2' => $request->shipping_address_line2,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_country' => $request->shipping_country,
                'shipping_phone' => $request->shipping_phone,
                'billing_address_line1' => $request->billing_address_line1,
                'billing_address_line2' => $request->billing_address_line2,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_postal_code' => $request->billing_postal_code,
                'billing_country' => $request->billing_country,
            ]);

            // Ajouter les articles
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'uuid' => Str::uuid(),
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_description' => $product->description,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? $product->tax_rate ?? 0,
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Recalculer les totaux
            $order->load('items');
            $order->calculateTotals();
            $order->save();

            // Ajouter l'historique
            $order->addHistory($order->status, 'Commande créée');

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Commande créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage())
                ->withInput();
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
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'discount_amount' => $order->discount_amount,
                'total' => $order->total,
                'notes' => 'Facture générée depuis la commande #' . $order->order_number,
            ]);

            // Créer les lignes de facture
            foreach ($order->items as $item) {
                $invoice->items()->create([
                    'uuid' => Str::uuid(),
                    'product_id' => $item->product_id,
                    'description' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
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
}
