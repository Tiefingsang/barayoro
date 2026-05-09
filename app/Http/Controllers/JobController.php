<?php
// app/Http/Controllers/JobController.php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\JobApplication;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobController extends Controller
{
    // Supprimer le constructeur avec middleware
    // Les routes publiques sont définies dans routes/web.php

    /**
     * Afficher la liste des offres d'emploi (visiteurs)
     */
    public function index(Request $request)
    {
        $query = JobOffer::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->with('company');

        if ($request->has('company_type') && $request->company_type) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('type', $request->company_type);
            });
        }

        if ($request->has('contract_type') && $request->contract_type) {
            $query->where('contract_type', $request->contract_type);
        }

        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('requirements', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(12);
        $companyTypes = Company::whereNotNull('type')->distinct()->pluck('type');

        return view('jobs.list', compact('jobs', 'companyTypes'));
    }

    /**
     * Afficher les détails d'une offre (visiteurs)
     */
    public function show($id)
    {
        $job = JobOffer::where('id', $id)
            ->where('is_active', true)
            ->with('company')
            ->firstOrFail();

        $job->increment('views');

        $similarJobs = JobOffer::where('company_id', $job->company_id)
            ->where('id', '!=', $job->id)
            ->where('is_active', true)
            ->limit(5)
            ->get();

        return view('jobs.details', compact('job', 'similarJobs'));
    }

    /**
     * Afficher le formulaire de candidature
     */
    public function apply($id)
    {
        $job = JobOffer::findOrFail($id);

        if (!$job->is_active) {
            return redirect()->route('jobs.list')->with('error', 'Cette offre n\'est plus disponible.');
        }

        return view('jobs.apply', compact('job'));
    }

    /**
     * Traiter la candidature
     */
    public function storeApplication(Request $request, $id)
    {
        $job = JobOffer::findOrFail($id);

        if (!$job->is_active) {
            return back()->with('error', 'Cette offre n\'est plus disponible.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'cover_letter' => 'required|string|min:100',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'expected_salary' => 'nullable|numeric|min:0',
            'available_from' => 'nullable|date',
        ]);

        $existingApplication = JobApplication::where('job_offer_id', $job->id)
            ->where('email', $request->email)
            ->first();

        if ($existingApplication) {
            return back()->with('warning', 'Vous avez déjà postulé à cette offre.');
        }

        $cvPath = $request->file('cv')->store('job_applications', 'public');

        $application = JobApplication::create([
            'uuid' => Str::uuid(),
            'job_offer_id' => $job->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'expected_salary' => $request->expected_salary,
            'available_from' => $request->available_from,
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('jobs.details', $job->id)
            ->with('success', 'Votre candidature a été envoyée avec succès !');
    }

    // ==================== PARTIE ADMIN ====================

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'contract_type' => 'required|in:cdi,cdd,stage,alternance,freelance',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'experience_level' => 'required|in:entry,intermediate,senior,expert',
            'expires_at' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);

        $job = JobOffer::create([
            'uuid' => Str::uuid(),
            'company_id' => Auth::user()->company_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'description' => $request->description,
            'requirements' => $request->requirements,
            'contract_type' => $request->contract_type,
            'location' => $request->location,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'experience_level' => $request->experience_level,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('jobs.list')->with('success', 'Offre d\'emploi créée avec succès.');
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $job = JobOffer::findOrFail($id);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $job = JobOffer::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'contract_type' => 'required|in:cdi,cdd,stage,alternance,freelance',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'experience_level' => 'required|in:entry,intermediate,senior,expert',
            'expires_at' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);

        $job->update($request->all());

        return redirect()->route('jobs.list')->with('success', 'Offre d\'emploi mise à jour avec succès.');
    }

    public function applications(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $applications = JobApplication::with('jobOffer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('jobs.applications', compact('applications'));
    }

    public function downloadCv($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $application = JobApplication::findOrFail($id);

        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            return back()->with('error', 'CV non trouvé.');
        }

        return Storage::disk('public')->download($application->cv_path);
    }
}