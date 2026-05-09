<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrangeMoneyService
{
    protected $apiUrl;
    protected $merchantKey;
    protected $apiSecret;
    protected $apiLogin;
    protected $apiPassword;
    protected $isSandbox;

    public function __construct()
    {
        $this->isSandbox = env('ORANGE_MONEY_SANDBOX', true);
        
        $this->apiUrl = $this->isSandbox 
            ? 'https://api.sandbox.orange.com/orange-money-webpay/dev/v1'
            : 'https://api.orange.com/orange-money-webpay/v1';
        
        $this->merchantKey = env('ORANGE_MONEY_MERCHANT_KEY', '');
        $this->apiSecret = env('ORANGE_MONEY_API_SECRET', '');
        $this->apiLogin = env('ORANGE_MONEY_LOGIN', '');
        $this->apiPassword = env('ORANGE_MONEY_PASSWORD', '');
    }

    /**
     * Générer le token d'accès
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->apiLogin, $this->apiPassword)
                ->asForm()
                ->post($this->apiUrl . '/oauth/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('Orange Money: Échec token', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Orange Money: Exception token', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Initier un paiement Orange Money
     */
    public function initiatePayment($company, $amount, $phoneNumber, $invoiceId = null, $clientId = null)
    {
        try {
            // Nettoyer le numéro
            $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
            
            // Générer une référence unique
            $paymentNumber = $this->generatePaymentNumber();
            $transactionId = 'OM-' . time() . '-' . Str::random(8);
            
            // Créer l'enregistrement de paiement (statut pending)
            $payment = Payment::create([
                'uuid' => Str::uuid(),
                'company_id' => $company instanceof Company ? $company->id : $company,
                'invoice_id' => $invoiceId,
                'client_id' => $clientId,
                'payment_number' => $paymentNumber,
                'payment_date' => now(),
                'amount' => $amount,
                'net_amount' => $amount,
                'method' => 'orange_money',
                'reference' => $transactionId,
                'transaction_id' => $transactionId,
                'mobile_number' => $phoneNumber,
                'mobile_operator' => 'orange',
                'status' => 'pending',
                'confirmation_status' => 'pending',
                'pending_since' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => json_encode([
                    'initiated_at' => now(),
                    'phone_number' => $phoneNumber,
                    'amount' => $amount
                ])
            ]);
            
            // En mode sandbox/test → simulation
            if ($this->isSandbox || env('APP_ENV') === 'local') {
                return $this->simulatePayment($payment);
            }
            
            // Mode production → API réelle
            $token = $this->getAccessToken();
            
            if (!$token) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => 'Impossible d\'obtenir le token Orange Money',
                    'last_error' => 'Token acquisition failed'
                ]);
                return ['success' => false, 'error' => 'Service Orange Money indisponible'];
            }
            
            $returnUrl = route('payments.orange-money.callback', ['payment_id' => $payment->id]);
            $notifyUrl = route('payments.orange-money.webhook');
            $cancelUrl = route('payments.orange-money.cancel', ['payment_id' => $payment->id]);
            
            $payload = [
                'merchant_key' => $this->merchantKey,
                'order_id' => $payment->id,
                'amount' => (string)$amount,
                'currency' => 'XOF',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'notif_url' => $notifyUrl,
                'lang' => 'fr',
                'customer_phone' => $phoneNumber,
                'customer_email' => auth()->user()->email ?? '',
            ];
            
            $response = Http::withToken($token)
                ->timeout(30)
                ->post($this->apiUrl . '/webpayment/payments', $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'SUCCESS') {
                    $payment->update([
                        'payment_url' => $data['payment_url'] ?? null,
                        'metadata' => json_encode(array_merge(
                            json_decode($payment->metadata ?? '{}', true),
                            ['api_response' => $data]
                        ))
                    ]);
                    
                    return [
                        'success' => true,
                        'payment' => $payment,
                        'payment_url' => $data['payment_url']
                    ];
                }
            }
            
            $payment->update([
                'status' => 'failed',
                'failure_reason' => 'Erreur API Orange Money',
                'last_error' => $response->body()
            ]);
            
            return ['success' => false, 'error' => 'Erreur de communication Orange Money'];
            
        } catch (\Exception $e) {
            Log::error('Orange Money: Exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Erreur technique: ' . $e->getMessage()];
        }
    }
    
    /**
     * Simulation de paiement (mode développement)
     */
    protected function simulatePayment(Payment $payment)
    {
        Log::info('Orange Money: Mode simulation', ['payment_id' => $payment->id]);
        
        // Générer une URL de simulation
        $simulateUrl = route('payments.orange-money.simulate', ['payment' => $payment->id]);
        
        $payment->update([
            'payment_url' => $simulateUrl,
            'metadata' => json_encode(array_merge(
                json_decode($payment->metadata ?? '{}', true),
                ['simulation_mode' => true]
            ))
        ]);
        
        return [
            'success' => true,
            'payment' => $payment,
            'payment_url' => $simulateUrl
        ];
    }
    
    /**
     * Confirmer un paiement (après succès)
     */
    public function confirmPayment(Payment $payment, $transactionData = [])
    {
        $payment->update([
            'status' => 'completed',
            'confirmation_status' => 'confirmed',
            'confirmed_at' => now(),
            'processed_at' => now(),
            'transaction_id' => $transactionData['transaction_id'] ?? $payment->transaction_id,
            'payment_details' => json_encode($transactionData),
            'webhook_data' => json_encode($transactionData)
        ]);
        
        // Mettre à jour la facture associée
        if ($payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice) {
                $invoice->paid += $payment->amount;
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
    
    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus(Payment $payment)
    {
        // En simulation
        if ($this->isSandbox || env('APP_ENV') === 'local') {
            return [
                'success' => true,
                'status' => $payment->status,
                'message' => $payment->status === 'completed' ? 'Payé' : 'En attente'
            ];
        }
        
        // En production, vérifier via l'API
        $token = $this->getAccessToken();
        
        if (!$token) {
            return ['success' => false, 'status' => 'pending', 'message' => 'Vérification impossible'];
        }
        
        $response = Http::withToken($token)
            ->get($this->apiUrl . '/webpayment/payments/' . $payment->id);
        
        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['status'] === 'SUCCESS') {
                $this->confirmPayment($payment, $data);
                return ['success' => true, 'status' => 'success'];
            }
        }
        
        return ['success' => true, 'status' => 'pending'];
    }
    
    /**
     * Gérer le webhook Orange Money
     */
    public function handleWebhook($data)
    {
        Log::info('Orange Money Webhook', $data);
        
        $paymentId = $data['order_id'] ?? $data['payment_id'] ?? null;
        
        if (!$paymentId) {
            return ['success' => false, 'error' => 'Payment ID manquant'];
        }
        
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            return ['success' => false, 'error' => 'Paiement non trouvé'];
        }
        
        $status = $data['status'] ?? $data['state'] ?? null;
        
        if ($status === 'SUCCESS' || $status === 'CONFIRMED') {
            $this->confirmPayment($payment, $data);
            return ['success' => true, 'message' => 'Paiement confirmé'];
        }
        
        $payment->update([
            'status' => 'failed',
            'failure_reason' => $data['message'] ?? 'Paiement échoué',
            'last_error' => json_encode($data)
        ]);
        
        return ['success' => false, 'error' => 'Paiement échoué'];
    }
    
    /**
     * Nettoyer le numéro de téléphone
     */
    protected function cleanPhoneNumber($phoneNumber)
    {
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '+223' . substr($cleaned, 1);
        }
        
        if (!str_starts_with($cleaned, '+')) {
            $cleaned = '+223' . $cleaned;
        }
        
        return $cleaned;
    }
    
    /**
     * Générer un numéro de paiement unique
     */
    protected function generatePaymentNumber()
    {
        $prefix = 'OM';
        $year = date('Y');
        $month = date('m');
        $random = Str::upper(Str::random(6));
        
        $number = $prefix . '-' . $year . $month . '-' . $random;
        
        // Vérifier l'unicité
        while (Payment::where('payment_number', $number)->exists()) {
            $random = Str::upper(Str::random(6));
            $number = $prefix . '-' . $year . $month . '-' . $random;
        }
        
        return $number;
    }
}