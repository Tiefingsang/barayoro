<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->checkPermission('view_payments');

        $query = Payment::where('company_id', $this->getCompanyId())
            ->with(['invoice', 'client', 'receiver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        $clients = Client::where('company_id', $this->getCompanyId())->get();

        $totalAmount = Payment::where('company_id', $this->getCompanyId())
            ->where('status', 'completed')
            ->sum('amount');

        return view('payments.index', compact('payments', 'clients', 'totalAmount'));
    }

    public function create(Request $request)
    {
        $this->checkPermission('create_payments');

        $invoice = null;
        if ($request->invoice_id) {
            $invoice = Invoice::where('company_id', $this->getCompanyId())
                ->where('id', $request->invoice_id)
                ->where('status', 'pending')
                ->where('balance', '>', 0)
                ->first();
        }

        $invoices = Invoice::where('company_id', $this->getCompanyId())
            ->where('status', 'pending')
            ->where('balance', '>', 0)
            ->with('client')
            ->orderBy('due_date')
            ->get();

        $clients = Client::where('company_id', $this->getCompanyId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $paymentNumber = $this->generatePaymentNumber();

        return view('payments.create', compact('invoices', 'clients', 'paymentNumber', 'invoice'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('create_payments');

        $validated = $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,bank_transfer,check,credit_card,mobile_money,other',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Si une facture est spécifiée, vérifier le montant
        if ($request->invoice_id) {
            $invoice = Invoice::find($request->invoice_id);
            if ($validated['amount'] > $invoice->balance) {
                return back()->with('error', 'Le montant du paiement dépasse le solde de la facture.')
                    ->withInput();
            }
        }

        $validated['company_id'] = $this->getCompanyId();
        $validated['received_by'] = auth()->id();
        $validated['payment_number'] = $this->generatePaymentNumber();
        $validated['net_amount'] = $validated['amount'];
        $validated['status'] = 'completed';
        $validated['confirmation_status'] = 'confirmed';
        $validated['confirmed_at'] = now();

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('payments', 'public');
            $validated['receipt_path'] = $path;
        }

        DB::beginTransaction();

        try {
            $payment = Payment::create($validated);

            // Mettre à jour la facture
            if ($request->invoice_id) {
                $invoice->paid += $validated['amount'];
                $invoice->balance = $invoice->total - $invoice->paid;

                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                    $invoice->paid_date = now();
                } else {
                    $invoice->status = 'pending';
                }

                $invoice->save();
            }

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Paiement enregistré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        $this->checkPermission('view_payments');
        $this->checkCompanyAccess($payment);

        $payment->load(['invoice', 'client', 'receiver']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->checkPermission('edit_payments');
        $this->checkCompanyAccess($payment);

        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Seuls les paiements en attente peuvent être modifiés.');
        }

        $invoices = Invoice::where('company_id', $this->getCompanyId())
            ->where('status', 'pending')
            ->where('balance', '>', 0)
            ->with('client')
            ->orderBy('due_date')
            ->get();

        $clients = Client::where('company_id', $this->getCompanyId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('payments.edit', compact('payment', 'invoices', 'clients'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->checkPermission('edit_payments');
        $this->checkCompanyAccess($payment);

        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Seuls les paiements en attente peuvent être modifiés.');
        }

        $validated = $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,bank_transfer,check,credit_card,mobile_money,other',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Si une facture est spécifiée, vérifier le montant
        if ($request->invoice_id) {
            $invoice = Invoice::find($request->invoice_id);
            if ($validated['amount'] > $invoice->balance + ($payment->invoice_id == $request->invoice_id ? $payment->amount : 0)) {
                return back()->with('error', 'Le montant du paiement dépasse le solde de la facture.')
                    ->withInput();
            }
        }

        $validated['net_amount'] = $validated['amount'];

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('payments', 'public');
            $validated['receipt_path'] = $path;
        }

        DB::beginTransaction();

        try {
            // Restaurer l'ancien paiement sur la facture
            if ($payment->invoice_id) {
                $oldInvoice = Invoice::find($payment->invoice_id);
                if ($oldInvoice) {
                    $oldInvoice->paid -= $payment->amount;
                    $oldInvoice->balance = $oldInvoice->total - $oldInvoice->paid;

                    if ($oldInvoice->balance > 0) {
                        $oldInvoice->status = 'pending';
                    }
                    $oldInvoice->save();
                }
            }

            $payment->update($validated);

            // Appliquer le nouveau paiement
            if ($payment->invoice_id) {
                $invoice = Invoice::find($payment->invoice_id);
                $invoice->paid += $payment->amount;
                $invoice->balance = $invoice->total - $invoice->paid;

                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                    $invoice->paid_date = now();
                } else {
                    $invoice->status = 'pending';
                }

                $invoice->save();
            }

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Paiement mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        $this->checkPermission('delete_payments');
        $this->checkCompanyAccess($payment);

        DB::beginTransaction();

        try {
            // Restaurer le paiement sur la facture
            if ($payment->invoice_id && $payment->status === 'completed') {
                $invoice = Invoice::find($payment->invoice_id);
                if ($invoice) {
                    $invoice->paid -= $payment->amount;
                    $invoice->balance = $invoice->total - $invoice->paid;

                    if ($invoice->balance > 0) {
                        $invoice->status = 'pending';
                    }
                    $invoice->save();
                }
            }

            $payment->delete();

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function receipt(Payment $payment)
    {
        $this->checkPermission('view_payments');
        $this->checkCompanyAccess($payment);

        if (!$payment->receipt_path) {
            abort(404, 'Reçu non trouvé.');
        }

        return response()->file(storage_path('app/public/' . $payment->receipt_path));
    }

    public function export(Request $request)
    {
        $this->checkPermission('export_reports');

        $query = Payment::where('company_id', $this->getCompanyId())
            ->with(['client', 'invoice']);

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        $filename = 'paiements_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['N°', 'Client', 'Facture', 'Montant', 'Date', 'Méthode', 'Référence', 'Statut']);

        foreach ($payments as $payment) {
            fputcsv($handle, [
                $payment->payment_number,
                $payment->client->name ?? 'N/A',
                $payment->invoice->invoice_number ?? 'N/A',
                number_format($payment->amount, 0, ',', ' ') . ' FCFA',
                $payment->payment_date,
                $payment->method,
                $payment->reference ?? '',
                $payment->status,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generatePaymentNumber()
    {
        $lastPayment = Payment::where('company_id', $this->getCompanyId())
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastPayment) {
            return 'PAY-001';
        }

        $lastNumber = intval(substr($lastPayment->payment_number, 4));
        $newNumber = $lastNumber + 1;

        return 'PAY-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
