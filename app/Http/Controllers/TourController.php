<?php
// app/Http/Controllers/TourController.php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TourController extends Controller
{
    // Supprimer le constructeur avec middleware

    public function index(Request $request)
    {
        $query = Tour::where('is_active', true)
            ->where('start_date', '>=', now());

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $tours = $query->orderBy('start_date')->paginate(12);
        $categories = Tour::distinct()->pluck('category');

        return view('tours.list', compact('tours', 'categories'));
    }

    public function show($id)
    {
        $tour = Tour::with(['bookings'])->findOrFail($id);
        $availableSpots = $tour->max_participants - $tour->bookings->where('status', 'confirmed')->count();

        return view('tours.details', compact('tour', 'availableSpots'));
    }

    public function book($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tour = Tour::findOrFail($id);
        $availableSpots = $tour->max_participants - $tour->bookings->where('status', 'confirmed')->count();

        if ($availableSpots <= 0) {
            return redirect()->route('tours.details', $tour->id)->with('error', 'Ce tour est complet.');
        }

        return view('tours.booking', compact('tour', 'availableSpots'));
    }

    public function storeBooking(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tour = Tour::findOrFail($id);

        $request->validate([
            'participants' => 'required|integer|min:1|max:' . $tour->max_participants,
            'special_requests' => 'nullable|string|max:500',
            'booking_date' => 'required|date|after:today',
        ]);

        $booking = TourBooking::create([
            'uuid' => Str::uuid(),
            'tour_id' => $tour->id,
            'user_id' => Auth::id(),
            'participants' => $request->participants,
            'special_requests' => $request->special_requests,
            'booking_date' => $request->booking_date,
            'status' => 'pending',
            'total_amount' => $tour->price * $request->participants,
        ]);

        return redirect()->route('tours.details', $tour->id)
            ->with('success', 'Réservation effectuée avec succès.');
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('tours.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_participants' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'boolean',
        ]);

        $tour = Tour::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_participants' => $request->max_participants,
            'location' => $request->location,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('image')) {
            $tour->update(['image' => $request->file('image')->store('tours', 'public')]);
        }

        return redirect()->route('tours.list')->with('success', 'Tour créé avec succès.');
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tour = Tour::findOrFail($id);
        return view('tours.edit', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $tour = Tour::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_participants' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'boolean',
        ]);

        $tour->update($request->except('image'));

        if ($request->hasFile('image')) {
            $tour->update(['image' => $request->file('image')->store('tours', 'public')]);
        }

        return redirect()->route('tours.list')->with('success', 'Tour mis à jour avec succès.');
    }
}