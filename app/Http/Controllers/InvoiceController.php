<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Afficher la liste des factures
     */
    public function index(Request $request)
    {
        $query = Invoice::where('company_id', Auth::user()->company_id)
                        ->with('client');

        // Filtrer par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('client', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrer par client
        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        // Trier
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $invoices = $query->paginate($request->get('per_page', 15));

        $clients = Client::where('company_id', Auth::user()->company_id)
                         ->where('status', 'active')
                         ->get();

        return view('invoices.index', compact('invoices', 'clients'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $clients = Client::where('company_id', Auth::user()->company_id)
                         ->where('status', 'active')
                         ->get();
                         //dd($clients);

        $products = Product::where('company_id', Auth::user()->company_id)
                           ->where('is_active', true)
                           ->get();

        return view('invoices.create', compact('clients', 'products'));
    }


    /**
     * Enregistrer une nouvelle facture
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Calculer les totaux
        $subtotal = 0;
        $tax = 0;
        $discount = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = ($item['discount'] ?? 0);
            $itemTaxRate = ($item['tax_rate'] ?? 0);
            $itemTax = ($itemSubtotal - $itemDiscount) * ($itemTaxRate / 100);

            $subtotal += $itemSubtotal;
            $discount += $itemDiscount;
            $tax += $itemTax;

            $itemsData[] = [
                'product_id' => $item['product_id'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'tax_amount' => $itemTax,
                'total' => ($itemSubtotal - $itemDiscount) + $itemTax,
            ];
        }

        $total = $subtotal - $discount + $tax;

        $invoice = Invoice::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'client_id' => $request->client_id,
            'created_by' => Auth::id(),
            'invoice_number' => $this->generateInvoiceNumber(),
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => 'draft',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'paid' => 0,
            'balance' => $total,
            'notes' => $request->notes,
            'terms' => $request->terms,
            'items' => $itemsData,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Afficher les détails d'une facture
     */
    public function show(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        $payments = $invoice->payments()->latest()->get();

        return view('invoices.show', compact('invoice', 'payments'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Seules les factures en brouillon peuvent être modifiées.');
        }

        $clients = Client::where('company_id', Auth::user()->company_id)
                         ->where('status', 'active')
                         ->get();

        $products = Product::where('company_id', Auth::user()->company_id)
                           ->where('is_active', true)
                           ->get();

        return view('invoices.edit', compact('invoice', 'clients', 'products'));
    }

    /**
     * Mettre à jour une facture
     */
    public function update(Request $request, Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Seules les factures en brouillon peuvent être modifiées.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Calculer les totaux
        $subtotal = 0;
        $tax = 0;
        $discount = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = ($item['discount'] ?? 0);
            $itemTaxRate = ($item['tax_rate'] ?? 0);
            $itemTax = ($itemSubtotal - $itemDiscount) * ($itemTaxRate / 100);

            $subtotal += $itemSubtotal;
            $discount += $itemDiscount;
            $tax += $itemTax;

            $itemsData[] = [
                'product_id' => $item['product_id'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'tax_amount' => $itemTax,
                'total' => ($itemSubtotal - $itemDiscount) + $itemTax,
            ];
        }

        $total = $subtotal - $discount + $tax;

        $invoice->update([
            'client_id' => $request->client_id,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'balance' => $total - $invoice->paid,
            'notes' => $request->notes,
            'terms' => $request->terms,
            'items' => $itemsData,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture mise à jour avec succès.');
    }

    /**
     * Supprimer une facture
     */
    public function destroy(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.index')
                ->with('error', 'Seules les factures en brouillon peuvent être supprimées.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    /**
     * Envoyer une facture par email
     */
    public function send(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        // Logique d'envoi d'email à implémenter
        $invoice->update(['status' => 'sent']);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture envoyée avec succès.');
    }

    /**
     * Marquer une facture comme payée
     */
    public function markAsPaid(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        $invoice->update([
            'status' => 'paid',
            'paid_date' => now(),
            'paid' => $invoice->total,
            'balance' => 0,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture marquée comme payée.');
    }

    /**
     * Générer le PDF de la facture
     */
    public function pdf(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Générer un numéro de facture unique
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
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

    /**
     * Vérifier que la facture appartient à l'entreprise
     */
    private function checkCompanyAccess(Invoice $invoice)
    {
        if ($invoice->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
