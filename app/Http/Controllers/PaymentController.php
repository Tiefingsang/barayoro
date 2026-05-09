<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\PaymentTransaction;
use App\Services\Payment\OrangeMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $orangeMoneyService;

    public function __construct(OrangeMoneyService $orangeMoneyService)
    {
        $this->middleware('auth');
        $this->orangeMoneyService = $orangeMoneyService;
    }

    // ... vos méthodes existantes (index, create, store, show, edit, update, destroy, receipt, export, generatePaymentNumber)

    /**
     * Afficher la page de paiement Orange Money pour l'abonnement
     */
    public function showOrangeMoneyPayment()
    {
        $company = auth()->user()->company;
        $amount = 49000; // 49 000 FCFA
        $plan = 'premium';
        
        return view('payments.orange-money-subscription', compact('company', 'amount', 'plan'));
    }

    /**
     * Payer une facture avec Orange Money
     */
    public function payInvoiceWithOrangeMoney(Invoice $invoice)
    {
        $this->checkCompanyAccess($invoice);
        
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cette facture est déjà payée.');
        }
        
        $amount = $invoice->balance;
        
        return view('payments.orange-money-invoice', compact('invoice', 'amount'));
    }

    /**
     * Initier un paiement Orange Money pour facture
     */
    public function initiateOrangeMoneyPayment(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:9|max:13',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $company = auth()->user()->company;
        $amount = $request->amount;
        
        // Vérifier si c'est pour une facture
        if ($request->invoice_id) {
            $invoice = Invoice::where('company_id', $company->id)
                ->where('id', $request->invoice_id)
                ->first();
                
            if (!$invoice) {
                return back()->with('error', 'Facture non trouvée.');
            }
            
            if ($amount > $invoice->balance) {
                return back()->with('error', 'Le montant dépasse le solde de la facture.');
            }
        }

        $result = $this->orangeMoneyService->initiatePayment($company, $amount, $request->phone_number, $request->invoice_id);

        if ($result['success']) {
            return redirect()->route('payments.orange-money.waiting', ['reference' => $result['reference']])
                ->with('success', 'Demande de paiement envoyée. Veuillez vérifier votre téléphone Orange Money.');
        }

        return back()->with('error', $result['error'] ?? 'Erreur lors de l\'initiation du paiement');
    }

    /**
     * Page d'attente de paiement Orange Money
     */
    public function waitingOrangeMoneyPayment($reference)
    {
        $transaction = PaymentTransaction::where('reference', $reference)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();
        
        return view('payments.orange-money-waiting', compact('transaction'));
    }

    /**
     * Vérifier le statut du paiement Orange Money
     */
    public function checkOrangeMoneyStatus($reference)
    {
        $result = $this->orangeMoneyService->checkPaymentStatus($reference);
        
        if ($result['success'] && $result['status'] === 'success') {
            return response()->json(['status' => 'success', 'message' => 'Paiement réussi']);
        }
        
        if ($result['status'] === 'pending') {
            return response()->json(['status' => 'pending', 'message' => 'Paiement en attente']);
        }
        
        return response()->json(['status' => 'failed', 'message' => $result['message'] ?? 'Paiement échoué']);
    }

    /**
     * Webhook pour Orange Money
     */
    public function orangeMoneyWebhook(Request $request)
    {
        $result = $this->orangeMoneyService->handleWebhook($request->all());
        
        if ($result['success']) {
            // Récupérer la transaction
            $transaction = PaymentTransaction::where('reference', $request['reference'])->first();
            
            if ($transaction && $transaction->invoice_id) {
                // Créer le paiement en base
                $this->createPaymentFromTransaction($transaction);
            }
            
            return response()->json(['status' => 'ok'], 200);
        }
        
        return response()->json(['status' => 'error', 'message' => $result['error']], 400);
    }

    /**
     * Créer un paiement à partir d'une transaction réussie
     */
    protected function createPaymentFromTransaction(PaymentTransaction $transaction)
    {
        $payment = Payment::create([
            'uuid' => Str::uuid(),
            'company_id' => $transaction->company_id,
            'invoice_id' => $transaction->invoice_id,
            'client_id' => $transaction->client_id,
            'received_by' => auth()->id(),
            'payment_number' => $this->generatePaymentNumber(),
            'payment_date' => now(),
            'amount' => $transaction->amount,
            'method' => 'mobile_money',
            'reference' => $transaction->reference,
            'transaction_id' => $transaction->transaction_id,
            'mobile_operator' => 'orange_money',
            'status' => 'completed',
            'confirmation_status' => 'confirmed',
            'confirmed_at' => now(),
            'notes' => 'Paiement via Orange Money',
        ]);

        // Mettre à jour la facture
        if ($transaction->invoice_id) {
            $invoice = Invoice::find($transaction->invoice_id);
            if ($invoice) {
                $invoice->paid += $transaction->amount;
                $invoice->balance = $invoice->total - $invoice->paid;
                
                if ($invoice->balance <= 0) {
                    $invoice->status = 'paid';
                    $invoice->paid_date = now();
                }
                $invoice->save();
            }
        }

        return $payment;
    }

    private function checkPermission($permission)
    {
        if (!auth()->user()->can($permission)) {
            abort(403, 'Vous n\'avez pas les droits nécessaires.');
        }
    }

    private function getCompanyId()
    {
        return auth()->user()->company_id;
    }

    private function checkCompanyAccess($model)
    {
        if ($model->company_id !== $this->getCompanyId()) {
            abort(403, 'Accès non autorisé.');
        }
    }
}