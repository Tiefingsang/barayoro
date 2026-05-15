<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\OrangeMoneyService;

class SubscriptionController extends Controller
{
    protected $orangeMoneyService;

    public function __construct(OrangeMoneyService $orangeMoneyService)
    {
        $this->orangeMoneyService = $orangeMoneyService;
    }

    /**
     * Afficher les plans d'abonnement
     */
    public function plans()
    {
        $company = Auth::user()->company;
        
        return view('subscription.plans', compact('company'));
    }

    /**
     * Page de checkout
     */
    public function checkout()
    {
        $company = Auth::user()->company;
        $plan = request('plan', 'annual');
        $amount = 49000; // 49 000 FCFA

        return view('subscription.checkout', compact('company', 'plan', 'amount'));
    }

    /**
     * Traiter le paiement
     */
    public function process(Request $request)
{
    // Validation de base
    $rules = [
        'payment_method' => 'required|in:card,orange_money,wave',
        'amount' => 'required|numeric|min:100',
        'plan' => 'required|string',
    ];

    if ($request->payment_method === 'card') {
        $rules['card_number'] = 'required|string|min:15|max:19';
        $rules['card_expiry'] = 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/';
        $rules['card_cvv'] = 'required|string|min:3|max:4';
    } elseif ($request->payment_method === 'orange_money') {
        $rules['mobile_number'] = [
            'required',
            'string',
            function ($attribute, $value, $fail) {
                // Nettoyer le numéro
                $cleanNumber = preg_replace('/[^0-9+]/', '', $value);
                
                // Vérifier le format
                $isValid = false;
                
                // Format international +223XXXXXXXX
                if (preg_match('/^\+223[0-9]{8}$/', $cleanNumber)) {
                    $localNumber = substr($cleanNumber, 4);
                    $prefix = substr($localNumber, 0, 2);
                    if (in_array($prefix, ['77', '70', '71', '79', '78', '76', ])) {
                        $isValid = true;
                    }
                }
                // Format local 0XXXXXXXX
                elseif (preg_match('/^0([0-9]{8})$/', $cleanNumber, $matches)) {
                    $prefix = substr($matches[1], 0, 2);
                    if (in_array($prefix, ['77', '70', '71', '79', '78', '76', ])) {
                        $isValid = true;
                    }
                }
                // Format simple 8 chiffres
                elseif (preg_match('/^([0-9]{8})$/', $cleanNumber, $matches)) {
                    $prefix = substr($matches[1], 0, 2);
                    if (in_array($prefix, ['77', '70', '71', '79', '78', '76', ])) {
                        $isValid = true;
                    }
                }
                
                if (!$isValid) {
                    $fail('Le numéro Orange Money n\'est pas valide. Utilisez un numéro Orange.');
                }
            },
        ];
    } elseif ($request->payment_method === 'wave') {
        $rules['mobile_number'] = 'required|string|min:8|max:13';
    }

    $validated = $request->validate($rules);
    
    // Formatage du numéro Orange Money
    if ($request->payment_method === 'orange_money') {
        $cleanNumber = preg_replace('/[^0-9]/', '', $request->mobile_number);
        
        // Enlever le 0 initial s'il existe
        if (strlen($cleanNumber) === 9 && substr($cleanNumber, 0, 1) === '0') {
            $cleanNumber = substr($cleanNumber, 1);
        }
        
        // S'assurer qu'on a 8 chiffres
        if (strlen($cleanNumber) === 8) {
            $formattedNumber = '+223' . $cleanNumber;
            $request->merge(['mobile_number' => $formattedNumber]);
        }
    }
    
    $company = Auth::user()->company;
    $amount = $request->amount;
    
    // Traitement Orange Money
    if ($request->payment_method === 'orange_money') {
        // Vérifier si le service Orange Money est disponible
        if (!$this->orangeMoneyService) {
            Log::error('OrangeMoneyService non disponible');
            return back()->with('error', 'Service de paiement temporairement indisponible. Veuillez réessayer plus tard.');
        }
        
        $result = $this->orangeMoneyService->initiatePayment(
            $company->id,
            $amount,
            $request->mobile_number,
            null, // invoice_id
            null  // client_id
        );
        
        Log::info('Résultat paiement Orange Money', [
            'success' => $result['success'] ?? false,
            'payment_url' => $result['payment_url'] ?? null,
            'error' => $result['error'] ?? null
        ]);
        
        if ($result['success'] && isset($result['payment_url'])) {
            // Rediriger vers la page de paiement Orange Money
            return redirect($result['payment_url']);
        }
        
        $errorMessage = $result['error'] ?? 'Erreur lors de l\'initialisation du paiement Orange Money';
        return back()->with('error', $errorMessage)->withInput();
    }
    
    // Traitement Wave (TODO)
    if ($request->payment_method === 'wave') {
        // À implémenter plus tard
        return back()->with('error', 'Wave sera bientôt disponible. Utilisez Orange Money pour le moment.');
    }
    
    // Traitement Carte bancaire (simulation pour le moment)
    if ($request->payment_method === 'card') {
        try {
            // Simulation de paiement par carte
            // À remplacer par Stripe ou autre service de paiement
            $paymentSuccess = true; // Simulation
            
            if ($paymentSuccess) {
                $company->update([
                    'subscription_status' => 'active',
                    'subscription_started_at' => now(),
                    'subscription_expires_at' => now()->addYear(),
                    'subscription_price' => $amount,
                    'last_payment_date' => now(),
                    'next_payment_date' => now()->addYear(),
                    'trial_ends_at' => null,
                ]);
                
                return redirect()->route('subscription.success')
                    ->with('success', 'Paiement effectué avec succès ! Votre abonnement est actif pour 1 an.');
            }
            
            return back()->with('error', 'Le paiement par carte a échoué. Veuillez réessayer ou utiliser Orange Money.');
            
        } catch (\Exception $e) {
            Log::error('Erreur paiement carte', ['error' => $e->getMessage()]);
            return back()->with('error', 'Une erreur technique est survenue. Veuillez réessayer.');
        }
    }
    
    return back()->with('error', 'Mode de paiement non supporté.');
}

    /**
     * Page de succès
     */
    public function success()
    {
        return view('subscription.success');
    }

    /**
     * Page d'abonnement expiré
     */
    public function expired()
    {
        return view('subscription.expired');
    }

    /**
     * Page d'abonnement requis
     */
    public function required()
    {
        return view('subscription.required');
    }
    
    /**
     * Callback Orange Money
     */
    public function callback(Request $request)
    {
        $paymentId = $request->query('payment_id');
        
        // Logique de vérification
        return redirect()->route('subscription.success')
            ->with('success', 'Paiement Orange Money réussi !');
    }
}