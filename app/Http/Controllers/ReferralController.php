<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le programme de parrainage
     */
    public function index()
    {
        $user = Auth::user();
        
        // Générer un code de parrainage si nécessaire
        if (!$user->referral_code) {
            $user->update(['referral_code' => $this->generateReferralCode()]);
        }

        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_referrals' => $referrals->count(),
            'successful_referrals' => $referrals->where('status', 'successful')->count(),
            'pending_referrals' => $referrals->where('status', 'pending')->count(),
            'total_rewards' => ReferralReward::where('user_id', $user->id)
                ->where('status', 'claimed')
                ->sum('amount'),
        ];

        $rewards = ReferralReward::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $shareUrl = route('register', ['ref' => $user->referral_code]);

        return view('referrals.index', compact('referrals', 'stats', 'rewards', 'shareUrl'));
    }

    /**
     * Traiter une inscription via parrainage
     */
    public function processReferral(Request $request)
    {
        $code = $request->get('ref');
        
        if (!$code) {
            return;
        }

        $referrer = \App\Models\User::where('referral_code', $code)->first();

        if (!$referrer || $referrer->id === Auth::id()) {
            return;
        }

        // Créer la référence
        Referral::create([
            'uuid' => Str::uuid(),
            'referrer_id' => $referrer->id,
            'referred_id' => Auth::id(),
            'code' => $code,
            'status' => 'pending',
            'expires_at' => now()->addDays(30),
        ]);

        session(['referral_code' => $code]);
    }

    /**
     * Marquer un parrainage comme réussi
     */
    public function markSuccessful(Referral $referral)
    {
        $this->checkAccess($referral);

        if ($referral->status !== 'pending') {
            return back()->with('error', 'Ce parrainage ne peut pas être modifié.');
        }

        $referral->update([
            'status' => 'successful',
            'completed_at' => now(),
        ]);

        // Créer la récompense
        $reward = ReferralReward::create([
            'uuid' => Str::uuid(),
            'user_id' => $referral->referrer_id,
            'referral_id' => $referral->id,
            'amount' => $this->getReferralAmount(),
            'type' => 'credit',
            'status' => 'pending',
            'description' => 'Récompense pour parrainage de ' . $referral->referred->name,
        ]);

        return back()->with('success', 'Parrainage marqué comme réussi !');
    }

    /**
     * Réclamer une récompense
     */
    public function claimReward(ReferralReward $reward)
    {
        if ($reward->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reward->status !== 'pending') {
            return back()->with('error', 'Cette récompense a déjà été réclamée.');
        }

        $reward->update([
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);

        // Appliquer le crédit à l'utilisateur
        $user = Auth::user();
        $user->update([
            'credits' => ($user->credits ?? 0) + $reward->amount
        ]);

        return back()->with('success', 'Récompense réclamée avec succès !');
    }

    private function generateReferralCode()
    {
        return strtoupper(Str::random(8));
    }

    private function getReferralAmount()
    {
        // Montant de la récompense (à configurer)
        return 5000; // 5000 FCFA par exemple
    }

    private function checkAccess($referral)
    {
        if ($referral->referrer_id !== Auth::id()) {
            abort(403);
        }
    }
}