<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
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
        //dd('Processus de paiement en cours...', $request->all());
        $rules = [
            'payment_method' => 'required|in:card,orange_money,wave',
            'amount' => 'required|numeric',
        ];

        // Ajout des règles conditionnelles selon le mode de paiement
        if ($request->payment_method === 'card') {
            $rules['card_number'] = 'required|string|min:15|max:19';
            $rules['card_expiry'] = 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/';
            $rules['card_cvv'] = 'required|string|min:3|max:4';
        } elseif (in_array($request->payment_method, ['orange_money', 'wave'])) {
            $rules['mobile_number'] = 'required|string|min:9|max:13';
        }

        $validated = $request->validate($rules);

        $company = Auth::user()->company;
        $amount = $request->amount;

        // Log pour debug
        Log::info('Paiement initié', [
            'company_id' => $company->id,
            'payment_method' => $request->payment_method,
            'amount' => $amount
        ]);

        // Simulation de paiement réussi (à remplacer par l'intégration réelle)
        $paymentSuccess = true;

        if ($paymentSuccess) {
            // Activer l'abonnement
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
                ->with('success', 'Paiement effectué avec succès ! Votre abonnement est actif.');
        }

        return back()->with('error', 'Le paiement a échoué. Veuillez réessayer.');
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
}
