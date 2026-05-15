<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Client;
use App\Services\Payment\OrangeMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $orangeMoneyService;

    public function __construct(OrangeMoneyService $orangeMoneyService)
    {
        $this->orangeMoneyService = $orangeMoneyService;
    }

    /**
     * Afficher la page de paiement Orange Money pour l'abonnement
     */
    public function showOrangeMoneyPayment()
    {
        $company = auth()->user()->company;
        $amount = 49000;
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
     * Initier un paiement Orange Money
     */
    public function initiateOrangeMoneyPayment(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:8|max:13',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:100'
        ]);

        $company = auth()->user()->company;
        $clientId = null;
        
        if ($request->invoice_id) {
            $invoice = Invoice::where('company_id', $company->id)
                ->where('id', $request->invoice_id)
                ->firstOrFail();
            
            if ($request->amount > $invoice->balance) {
                return back()->with('error', 'Le montant dépasse le solde dû');
            }
            
            $clientId = $invoice->client_id;
        }
        
        $result = $this->orangeMoneyService->initiatePayment(
            $company->id,
            $request->amount,
            $request->phone_number,
            $request->invoice_id,
            $clientId
        );
        
        if ($result['success']) {
            return redirect($result['payment_url']);
        }
        
        return back()->with('error', $result['error'] ?? 'Erreur lors du paiement');
    }

    /**
     * Callback après paiement Orange Money
     */
    public function orangeMoneyCallback(Request $request)
    {
        $paymentId = $request->query('payment_id') ?? $request->query('order_id');
        
        if (!$paymentId) {
            return redirect()->route('invoices.index')
                ->with('error', 'Référence de paiement manquante');
        }
        
        $payment = Payment::where('id', $paymentId)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();
        
        $result = $this->orangeMoneyService->checkPaymentStatus($payment);
        
        if ($result['status'] === 'success') {
            return redirect()->route('payments.show', $payment)
                ->with('success', 'Paiement effectué avec succès !');
        }
        
        return redirect()->route('payments.orange-money.waiting', ['payment' => $payment->id])
            ->with('warning', 'Paiement en cours de vérification');
    }

    /**
     * Page d'attente
     */
    public function waitingOrangeMoneyPayment(Payment $payment)
    {
        $this->checkCompanyAccess($payment);
        
        return view('payments.orange-money-waiting', compact('payment'));
    }

    /**
     * Simulation (développement uniquement)
     */
    public function orangeMoneySimulate(Payment $payment)
    {
        $this->orangeMoneyService->confirmPayment($payment, [
            'transaction_id' => 'SIM-' . time(),
            'simulated_at' => now()
        ]);
        
        return redirect()->route('payments.show', $payment)
            ->with('success', '✅ Paiement simulé avec succès (mode développement)');
    }

    /**
     * Webhook Orange Money
     */
    public function orangeMoneyWebhook(Request $request)
    {
        $result = $this->orangeMoneyService->handleWebhook($request->all());
        
        if ($result['success']) {
            return response()->json(['status' => 'ok'], 200);
        }
        
        return response()->json(['status' => 'error', 'message' => $result['error']], 400);
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

    // Méthodes privées
    private function generatePaymentNumber()
    {
        return 'PAY-' . time() . '-' . Str::random(6);
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

    /**
 * Afficher les détails d'un paiement
 */
public function show($id)
{
    $payment = Payment::where('company_id', $this->getCompanyId())
        ->with(['invoice', 'client'])
        ->findOrFail($id);
    
    $this->checkCompanyAccess($payment);
    
    return view('payments.show', compact('payment'));
}
}