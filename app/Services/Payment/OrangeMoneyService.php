<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrangeMoneyService
{
    protected $apiUrl;
    protected $merchantKey;
    protected $apiLogin;
    protected $apiPassword;
    protected $isSandbox;

    public function __construct()
    {
        $this->isSandbox = env('ORANGE_MONEY_SANDBOX', true);
        
        // URLs selon la documentation Orange Money
        if ($this->isSandbox) {
            $this->apiUrl = 'https://api.orange.com/orange-money-webpay/dev/v1';
        } else {
            $this->apiUrl = 'https://api.orange.com/orange-money-webpay/v1';
        }
        
        $this->merchantKey = env('ORANGE_MONEY_MERCHANT_KEY', '');
        $this->apiLogin = env('ORANGE_MONEY_LOGIN', '');
        $this->apiPassword = env('ORANGE_MONEY_PASSWORD', '');
    }

    /**
     * Générer le token d'accès (2-legged OAuth)
     * Valable 90 jours selon la doc
     */
    protected function getAccessToken()
    {
        try {
            $url = $this->apiUrl . '/oauth/token';
            
            Log::info('Orange Money: Demande de token', ['url' => $url]);
            
            $response = Http::withBasicAuth($this->apiLogin, $this->apiPassword)
                ->asForm()
                ->post($url, [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Orange Money: Token obtenu', ['expires_in' => $data['expires_in'] ?? 'unknown']);
                return $data['access_token'];
            }

            Log::error('Orange Money: Échec token', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Orange Money: Exception token', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Initier un paiement Orange Money
     * Selon documentation section 3.1.1
     */
    public function initiatePayment($companyId, $amount, $phoneNumber, $invoiceId = null, $clientId = null)
    {
        try {
            // Nettoyer le numéro
            $phoneNumber = $this->cleanPhoneNumber($phoneNumber);
            
            // Générer un order_id unique (max 30 caractères)
            $orderId = 'ORD-' . time() . '-' . Str::random(6);
            
            // Générer un numéro de paiement
            $paymentNumber = $this->generatePaymentNumber();
            
            // Créer l'enregistrement de paiement
            $payment = Payment::create([
                'uuid' => Str::uuid(),
                'company_id' => $companyId,
                'invoice_id' => $invoiceId,
                'client_id' => $clientId,
                'payment_number' => $paymentNumber,
                'payment_date' => now(),
                'amount' => $amount,
                'net_amount' => $amount,
                'method' => 'orange_money',
                'reference' => $orderId,
                'transaction_id' => $orderId,
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
            
            // Obtenir le token
            $token = $this->getAccessToken();
            
            if (!$token) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => 'Impossible d\'obtenir le token Orange Money'
                ]);
                return ['success' => false, 'error' => 'Service Orange Money indisponible veuillez contacter le +223 92 51 64 05'];
            }
            
            // Construction de l'URL de retour
            $baseUrl = config('app.url');
            $returnUrl = $baseUrl . '/payments/orange-money/callback?payment_id=' . $payment->id;
            $cancelUrl = $baseUrl . '/payments/orange-money/cancel?payment_id=' . $payment->id;
            $notifyUrl = $baseUrl . '/api/webhooks/orange-money';
            
            // Payload selon documentation section 3.1.1
            // Attention: En mode DEV (Sandbox), la devise doit être "OUV"
            $payload = [
                'merchant_key' => $this->merchantKey,
                'currency' => $this->isSandbox ? 'OUV' : 'XOF',  // Sandbox: OUV, Production: XOF
                'order_id' => $orderId,
                'amount' => (int)$amount,
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'notif_url' => $notifyUrl,
                'lang' => 'fr',
                'reference' => 'Barayoro-' . $paymentNumber  // référence marchand (max 30 chars)
            ];
            
            Log::info('Orange Money: Initiation paiement', ['payload' => $payload]);
            
            // Appel API selon documentation section 3.1.1
            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->timeout(30)
                ->post($this->apiUrl . '/webpayment', $payload);
            
            Log::info('Orange Money: Réponse API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] == 201) {
                    // Mettre à jour le paiement avec le pay_token et payment_url
                    $payment->update([
                        'payment_url' => $data['payment_url'] ?? null,
                        'metadata' => json_encode([
                            'pay_token' => $data['pay_token'] ?? null,
                            'notif_token' => $data['notif_token'] ?? null,
                            'api_response' => $data
                        ])
                    ]);
                    
                    return [
                        'success' => true,
                        'payment' => $payment,
                        'payment_url' => $data['payment_url'],
                        'pay_token' => $data['pay_token'] ?? null
                    ];
                }
                
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $data['message'] ?? 'Erreur API',
                    'last_error' => json_encode($data)
                ]);
                
                return [
                    'success' => false, 
                    'error' => $data['message'] ?? 'Erreur lors de l\'initiation'
                ];
            }
            
            // Gestion des erreurs HTTP
            $errorMessage = 'Erreur HTTP: ' . $response->status();
            if ($response->status() == 401) {
                $errorMessage = 'Clés API invalides. Vérifiez votre configuration.';
            } elseif ($response->status() == 400) {
                $errorMessage = 'Requête invalide: ' . $response->body();
            }
            
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $errorMessage,
                'last_error' => $response->body()
            ]);
            
            return ['success' => false, 'error' => $errorMessage];
            
        } catch (\Exception $e) {
            Log::error('Orange Money: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return ['success' => false, 'error' => 'Erreur technique: ' . $e->getMessage()];
        }
    }

    /**
     * Vérifier le statut d'une transaction
     * Selon documentation section 4
     */
    public function checkPaymentStatus(Payment $payment)
    {
        try {
            $metadata = json_decode($payment->metadata ?? '{}', true);
            $payToken = $metadata['pay_token'] ?? null;
            
            if (!$payToken) {
                // Récupérer via API de statut
                $token = $this->getAccessToken();
                if (!$token) {
                    return ['success' => false, 'status' => 'pending', 'message' => 'Impossible de vérifier'];
                }
                
                $payload = [
                    'order_id' => $payment->reference,
                    'amount' => (int)$payment->amount,
                    'pay_token' => $payToken
                ];
                
                $response = Http::withToken($token)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->post($this->apiUrl . '/transactionstatus', $payload);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $status = $data['status'] ?? 'INITIATED';
                    
                    if ($status === 'SUCCESS') {
                        $payment->update([
                            'status' => 'completed',
                            'confirmation_status' => 'confirmed',
                            'confirmed_at' => now(),
                            'transaction_id' => $data['txnid'] ?? $payment->transaction_id
                        ]);
                        return ['success' => true, 'status' => 'success'];
                    } elseif ($status === 'FAILED') {
                        $payment->update(['status' => 'failed']);
                        return ['success' => true, 'status' => 'failed'];
                    }
                    
                    return ['success' => true, 'status' => 'pending'];
                }
            }
            
            return ['success' => true, 'status' => $payment->status];
            
        } catch (\Exception $e) {
            Log::error('Orange Money: Erreur vérification', ['message' => $e->getMessage()]);
            return ['success' => false, 'status' => 'pending'];
        }
    }

    /**
     * Gérer la notification webhook
     * Selon documentation section 3.3
     */
    public function handleWebhook($data)
    {
        Log::info('Orange Money: Webhook reçu', $data);
        
        // Vérifier la présence du notif_token (section 3.3)
        $notifToken = $data['notif_token'] ?? null;
        $status = $data['status'] ?? null;
        $txnId = $data['txnid'] ?? null;
        
        // Chercher le paiement par le notif_token stocké
        $payment = Payment::where('metadata->notif_token', $notifToken)->first();
        
        if (!$payment && isset($data['order_id'])) {
            $payment = Payment::where('reference', $data['order_id'])->first();
        }
        
        if (!$payment) {
            Log::error('Orange Money: Paiement non trouvé pour webhook', $data);
            return ['success' => false, 'error' => 'Payment not found'];
        }
        
        if ($status === 'SUCCESS') {
            $payment->update([
                'status' => 'completed',
                'confirmation_status' => 'confirmed',
                'confirmed_at' => now(),
                'transaction_id' => $txnId,
                'webhook_data' => json_encode($data)
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
            
            return ['success' => true, 'message' => 'Paiement confirmé'];
        }
        
        $payment->update([
            'status' => 'failed',
            'failure_reason' => 'Paiement échoué via webhook',
            'webhook_data' => json_encode($data)
        ]);
        
        return ['success' => false, 'error' => 'Payment failed'];
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
            'payment_details' => json_encode($transactionData)
        ]);
        
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
        
        while (Payment::where('payment_number', $number)->exists()) {
            $random = Str::upper(Str::random(6));
            $number = $prefix . '-' . $year . $month . '-' . $random;
        }
        
        return $number;
    }
}