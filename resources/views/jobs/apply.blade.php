{{-- resources/views/pages/job-apply.blade.php --}}
@extends('layouts.master')

@section('title', 'Postuler - ' . $job->title)
@section('description', 'Postulez pour le poste de ' . $job->title . ' chez ' . ($job->company->name ?? 'Barayoro'))

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Offres d\'emploi', 'url' => route('jobs.list')],
        ['label' => $job->title, 'url' => route('jobs.details', $job->id)],
        ['label' => 'Postuler']
    ]" />

    <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="max-w-4xl mx-auto">
                <!-- En-tête avec informations sur le poste -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                    <div class="gradient-bg px-8 py-8 text-center md:text-left">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div>
                                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full mb-3">
                                    <i class="fas fa-briefcase text-white text-xs"></i>
                                    <span class="text-white text-sm font-semibold">{{ strtoupper($job->contract_type ?? 'CDI') }}</span>
                                </div>
                                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $job->title }}</h1>
                                <div class="flex flex-wrap gap-4 text-orange-100">
                                    <span class="inline-flex items-center gap-2">
                                        <i class="fas fa-building"></i>
                                        {{ $job->company->name ?? 'Barayoro' }}
                                    </span>
                                    <span class="inline-flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $job->location ?? 'Bamako, Mali' }}
                                    </span>
                                    @if($job->salary_min && $job->salary_max)
                                    <span class="inline-flex items-center gap-2">
                                        <i class="fas fa-money-bill-wave"></i>
                                        {{ number_format($job->salary_min, 0, ',', ' ') }} - {{ number_format($job->salary_max, 0, ',', ' ') }} FCFA
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-3">
                                    <p class="text-white text-sm">Date limite</p>
                                    <p class="text-white font-bold">
                                        {{ $job->expires_at ? $job->expires_at->format('d/m/Y') : 'Non spécifiée' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de candidature -->
                    <div class="p-8 md:p-10">
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center gap-3">
                                <i class="fas fa-check-circle text-xl"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error') || session('warning'))
                            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                                <span>{{ session('error') ?? session('warning') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                                <div class="flex items-center gap-3 mb-2">
                                    <i class="fas fa-times-circle text-xl"></i>
                                    <span class="font-semibold">Veuillez corriger les erreurs suivantes :</span>
                                </div>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('jobs.apply.store', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <!-- Honeypot anti-spam -->
                            <input type="text" name="_website" style="display:none" tabindex="-1" autocomplete="off">

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Nom complet -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" name="full_name" required value="{{ old('full_name') }}" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition"
                                               placeholder="Jean Dupont">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" name="email" required value="{{ old('email') }}" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition"
                                               placeholder="jean@example.com">
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Téléphone -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Téléphone
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone-alt text-gray-400"></i>
                                        </div>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition"
                                               placeholder="+223 XX XX XX XX">
                                    </div>
                                </div>

                                <!-- Salaire souhaité -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Salaire souhaité (FCFA)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-money-bill-wave text-gray-400"></i>
                                        </div>
                                        <input type="number" name="expected_salary" value="{{ old('expected_salary') }}" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition"
                                               placeholder="250000">
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Date de disponibilité -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Date de disponibilité
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                        <input type="date" name="available_from" value="{{ old('available_from') }}" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition">
                                    </div>
                                </div>

                                <!-- CV -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        CV <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-file-alt text-gray-400"></i>
                                        </div>
                                        <input type="file" name="cv" required accept=".pdf,.doc,.docx" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-custom hover:file:bg-orange-100">
                                    </div>
                                    <p class="text-gray-500 text-xs mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>Formats acceptés : PDF, DOC, DOCX. Taille max : 5MB
                                    </p>
                                </div>
                            </div>

                            <!-- Lettre de motivation -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Lettre de motivation <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <i class="fas fa-pen-fancy text-gray-400"></i>
                                    </div>
                                    <textarea name="cover_letter" rows="8" required 
                                              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-custom focus:border-orange-custom transition resize-none"
                                              placeholder="Décrivez votre parcours, vos compétences et votre motivation pour ce poste...">{{ old('cover_letter') }}</textarea>
                                </div>
                                <p class="text-gray-500 text-xs mt-2">
                                    <i class="fas fa-lightbulb mr-1"></i>Conseil : Personnalisez votre lettre pour mettre en avant vos compétences pertinentes.
                                </p>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                <button type="submit" class="flex-1 px-8 py-4 gradient-bg text-white rounded-xl font-semibold btn-primary inline-flex items-center justify-center gap-2 group">
                                    <i class="fas fa-paper-plane group-hover:translate-x-1 transition"></i>
                                    Envoyer ma candidature
                                </button>
                                <a href="{{ route('jobs.details', $job->id) }}" 
                                   class="flex-1 px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Conseils pour candidature -->
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">Conseils pour votre candidature</h3>
                            <ul class="text-gray-600 text-sm space-y-1">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                    Soignez votre lettre de motivation et personnalisez-la pour ce poste
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                    Assurez-vous que votre CV est à jour et bien structuré
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                    Vérifiez bien vos coordonnées avant d'envoyer
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                    Vous recevrez une confirmation par email après envoi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection