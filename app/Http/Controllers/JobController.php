<?php

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
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'apply', 'storeApplication']);
    }

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

        // Filtre par type d'entreprise
        if ($request->has('company_type') && $request->company_type) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('type', $request->company_type);
            });
        }

        // Filtre par type de contrat
        if ($request->has('contract_type') && $request->contract_type) {
            $query->where('contract_type', $request->contract_type);
        }

        // Filtre par localisation
        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Recherche par mots-clés
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('requirements', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(12);

        // Récupérer les types d'entreprises uniques pour le filtre
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

        // Incrémenter les vues
        $job->increment('views');

        // Offres similaires
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

        // Vérifier si le candidat a déjà postulé
        $existingApplication = JobApplication::where('job_offer_id', $job->id)
            ->where('email', $request->email)
            ->first();

        if ($existingApplication) {
            return back()->with('warning', 'Vous avez déjà postulé à cette offre.');
        }

        // Gérer le CV
        $cvPath = $request->file('cv')->store('job_applications', 'public');

        // Vérifier si le candidat existe déjà dans le système d'alerte
        $knownCandidate = $this->checkKnownCandidate($request->email);

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

        // Notifier l'entreprise
        $this->notifyCompany($job, $application);

        // Si candidat connu, envoyer une alerte spéciale
        if ($knownCandidate) {
            $this->sendKnownCandidateAlert($job, $application);
        }

        return redirect()->route('jobs.details', $job->id)
            ->with('success', 'Votre candidature a été envoyée avec succès !');
    }

    /**
     * Vérifier si le candidat est déjà connu (système d'alerte)
     */
    private function checkKnownCandidate($email)
    {
        // Vérifier dans les candidatures précédentes
        $existingApplications = JobApplication::where('email', $email)->count();
        
        if ($existingApplications >= 2) {
            return true;
        }

        return false;
    }

    /**
     * Envoyer une alerte pour candidat connu
     */
    private function sendKnownCandidateAlert($job, $application)
    {
        // Logique d'alerte - peut être stockée en base ou envoyée par email
        \Log::info('CANDIDAT_CONNU', [
            'job_id' => $job->id,
            'job_title' => $job->title,
            'candidate_email' => $application->email,
            'candidate_name' => $application->full_name,
        ]);

        // Créer une notification dans le système
        \App\Models\Notification::create([
            'uuid' => Str::uuid(),
            'company_id' => $job->company_id,
            'user_id' => $job->company->owner_id ?? null,
            'type' => 'known_candidate',
            'title' => 'Candidat connu',
            'message' => "Un candidat déjà connu a postulé à l'offre: {$job->title}",
            'data' => ['application_id' => $application->id],
            'sent_at' => now(),
        ]);
    }

    /**
     * Notifier l'entreprise d'une nouvelle candidature
     */
    private function notifyCompany($job, $application)
    {
        // Logique de notification par email
        \Log::info('NOUVELLE_CANDIDATURE', [
            'company_id' => $job->company_id,
            'job_title' => $job->title,
            'candidate' => $application->full_name,
        ]);
    }

    // ==================== PARTIE ADMIN ====================

    /**
     * Afficher la liste des offres (admin)
     */
    public function create()
    {
        $this->checkPermission('create_jobs');

        return view('jobs.create');
    }

    /**
     * Enregistrer une nouvelle offre (admin)
     */
    public function store(Request $request)
    {
        $this->checkPermission('create_jobs');

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

        return redirect()->route('jobs.list')
            ->with('success', 'Offre d\'emploi créée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition (admin)
     */
    public function edit($id)
    {
        $this->checkPermission('edit_jobs');

        $job = JobOffer::findOrFail($id);
        $this->checkCompanyAccess($job);

        return view('jobs.edit', compact('job'));
    }

    /**
     * Mettre à jour une offre (admin)
     */
    public function update(Request $request, $id)
    {
        $this->checkPermission('edit_jobs');

        $job = JobOffer::findOrFail($id);
        $this->checkCompanyAccess($job);

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

        return redirect()->route('jobs.list')
            ->with('success', 'Offre d\'emploi mise à jour avec succès.');
    }

    /**
     * Lister les candidatures reçues (admin)
     */
    public function applications(Request $request, $id = null)
    {
        $this->checkPermission('view_job_applications');

        $query = JobApplication::whereHas('jobOffer', function($q) {
            $q->where('company_id', Auth::user()->company_id);
        })->with('jobOffer');

        if ($id) {
            $query->where('job_offer_id', $id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('jobs.applications', compact('applications'));
    }

    /**
     * Mettre à jour le statut d'une candidature
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $this->checkPermission('edit_job_applications');

        $application = JobApplication::findOrFail($id);
        $this->checkCompanyAccess($application->jobOffer);

        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'reviewer_notes' => $request->notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Statut de candidature mis à jour.');
    }

    /**
     * Télécharger le CV d'un candidat
     */
    public function downloadCv($id)
    {
        $this->checkPermission('view_job_applications');

        $application = JobApplication::findOrFail($id);
        $this->checkCompanyAccess($application->jobOffer);

        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            return back()->with('error', 'CV non trouvé.');
        }

        return Storage::disk('public')->download($application->cv_path);
    }

    private function checkCompanyAccess($job)
    {
        if ($job->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }

    private function checkPermission($permission)
    {
        if (!Auth::user()->can($permission)) {
            abort(403, 'Permission non accordée.');
        }
    }
}