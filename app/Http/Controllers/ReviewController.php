<?php
// app/Http/Controllers/ReviewController.php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Afficher la liste des avis (admin)
     */
    public function index(Request $request)
    {
        // Supprimer ou commenter la vérification de permission
        // $this->checkPermission('manage_reviews');

        $query = Review::where('company_id', Auth::user()->company_id)
            ->with(['user', 'reviewable']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Review::where('company_id', Auth::user()->company_id)->count(),
            'pending' => Review::where('company_id', Auth::user()->company_id)->where('status', 'pending')->count(),
            'approved' => Review::where('company_id', Auth::user()->company_id)->where('status', 'approved')->count(),
            'rejected' => Review::where('company_id', Auth::user()->company_id)->where('status', 'rejected')->count(),
            'average_rating' => Review::where('company_id', Auth::user()->company_id)->where('status', 'approved')->avg('rating') ?? 0,
        ];

        return view('reviews.manage', compact('reviews', 'stats'));
    }

    /**
     * Page de gestion des avis (alias)
     */
    public function manage(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Approuver un avis
     */
    public function approve(Review $review)
    {
        if ($review->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Avis approuvé avec succès.');
    }

    /**
     * Rejeter un avis
     */
    public function reject(Request $request, Review $review)
    {
        if ($review->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $review->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        return back()->with('success', 'Avis rejeté.');
    }

    /**
     * Supprimer un avis
     */
    public function destroy(Review $review)
    {
        if ($review->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Avis supprimé avec succès.');
    }

    /**
     * Poster un avis (client)
     */
    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        $reviewable = $request->reviewable_type::find($request->reviewable_id);

        if (!$reviewable) {
            return back()->with('error', 'Ressource non trouvée.');
        }

        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_type', $request->reviewable_type)
            ->where('reviewable_id', $request->reviewable_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour cette ressource.');
        }

        $review = Review::create([
            'uuid' => Str::uuid(),
            'company_id' => $reviewable->company_id ?? Auth::user()->company_id,
            'user_id' => Auth::id(),
            'reviewable_type' => $request->reviewable_type,
            'reviewable_id' => $request->reviewable_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Merci pour votre avis ! Il sera publié après modération.');
    }
}