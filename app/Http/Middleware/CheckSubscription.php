<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 🔥 NOUVEAU : Si l'utilisateur est ADMIN, on ignore la vérification d'abonnement
        if ($user && $user->hasRole('admin')) {
            return $next($request);
        }

        if (!$user || !$user->company) {
            return redirect()->route('subscription.required');
        }

        $company = $user->company;

        // Log pour déboguer
        \Log::info('Subscription check', [
            'user_id' => $user->id,
            'user_role' => $user->getRoleNames()->first(),
            'company_id' => $company->id,
            'status' => $company->subscription_status ?? 'null',
            'trial_ends_at' => $company->trial_ends_at ?? 'null',
            'subscription_expires_at' => $company->subscription_expires_at ?? 'null',
        ]);

        // Cas 1: Période d'essai
        if ($company->subscription_status === 'trial') {
            if ($company->trial_ends_at && $company->trial_ends_at->isFuture()) {
                return $next($request);
            } else {
                $company->update(['subscription_status' => 'expired']);
                return redirect()->route('subscription.expired');
            }
        }

        // Cas 2: Abonnement actif
        if ($company->subscription_status === 'active') {
            if ($company->subscription_expires_at && $company->subscription_expires_at->isFuture()) {
                return $next($request);
            } else {
                $company->update(['subscription_status' => 'expired']);
                return redirect()->route('subscription.expired');
            }
        }

        // Cas 3: En attente de paiement
        if ($company->subscription_status === 'pending') {
            return redirect()->route('subscription.plans')
                ->with('warning', 'Veuillez finaliser votre paiement pour activer votre abonnement.');
        }

        // Cas 4: Expiré ou autre
        return redirect()->route('subscription.expired');
    }
}
