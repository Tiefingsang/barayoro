<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'payment_method' => 'required|string',
            'card_number' => 'required_if:payment_method,card|string',
            'card_expiry' => 'required_if:payment_method,card|string',
            'card_cvv' => 'required_if:payment_method,card|string',
            'mobile_number' => 'required_if:payment_method,orange_money,wave|string',
        ]);

        $company = Auth::user()->company;

        // Logique de paiement à implémenter selon le moyen de paiement
        // Orange Money, Wave, Carte bancaire, etc.

        // Simuler un paiement réussi
        $paymentSuccess = true;

        if ($paymentSuccess) {
            // Activer l'abonnement
            $company->activateSubscription();

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
