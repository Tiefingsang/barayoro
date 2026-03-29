@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

        <!-- Fil d'Ariane -->
        <div class="white-box xxxl:p-6">
          <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails de l'utilisateur</h2>
            <ul class="flex flex-wrap gap-2 items-center">
              <li>
                <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                  <i class="las la-home shrink-0"></i>
                  <span>Accueil</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2" href="{{ route('users.index') }}">
                  <i class="las la-users shrink-0"></i>
                  <span>Utilisateurs</span>
                </a>
              </li>
              <li class="text-sm text-neutral-100">•</li>
              <li>
                <a class="flex items-center gap-2 text-primary-300" href="#">
                  <i class="las la-user-circle shrink-0"></i>
                  <span>Détails</span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Carte utilisateur -->
        <div class="white-box">
          <div class="grid grid-cols-12 gap-4 lg:gap-6">

            <!-- Colonne Profil -->
            <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
              <div class="flex flex-col items-center text-center">
                <!-- Avatar -->
                <div class="relative mb-4">
                  <div class="size-32 lg:size-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                  </div>
                  <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                    @if($user->is_active)
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                    @else
                      <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactif</span>
                    @endif
                  </div>
                </div>

                <!-- Nom et rôle -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ $user->position ?? 'Aucune fonction' }}</p>

                <!-- Badge rôle -->
                <div class="mt-2">
                  @if($userRole == 'admin')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Administrateur</span>
                  @elseif($userRole == 'manager')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Gestionnaire</span>
                  @else
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Employé</span>
                  @endif
                </div>

                <!-- Statistiques rapides -->
                <div class="grid grid-cols-3 gap-2 w-full mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->tasks_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Tâches</p>
                  </div>
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->projects_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Projets</p>
                  </div>
                  <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->time_entries_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Heures</p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 mt-6 w-full">
                  @can('edit_users')
                  <a href="{{ route('users.edit', $user) }}" class="flex-1 px-4 py-2 text-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="las la-edit mr-1"></i> Modifier
                  </a>
                  @endcan
                  <a href="{{ route('users.index') }}" class="flex-1 px-4 py-2 text-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <i class="las la-arrow-left mr-1"></i> Retour
                  </a>
                </div>
              </div>
            </div>

            <!-- Colonne Informations -->
            <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
              <div class="space-y-6">

                <!-- Informations personnelles -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-user-circle mr-2"></i> Informations personnelles
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Nom complet</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->name }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Email</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        {{ $user->email }}
                        @if($user->email_verified_at)
                          <span class="ml-2 text-xs text-green-600"><i class="las la-check-circle"></i> Vérifié</span>
                        @else
                          <span class="ml-2 text-xs text-yellow-600"><i class="las la-clock"></i> Non vérifié</span>
                        @endif
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Téléphone</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Fonction</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->position ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Matricule</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->employee_id ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Date d'embauche</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->hire_date ? $user->hire_date->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Type d'emploi</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        @if($user->employment_type == 'full_time')
                          Temps plein
                        @elseif($user->employment_type == 'part_time')
                          Temps partiel
                        @elseif($user->employment_type == 'contract')
                          Contractuel
                        @elseif($user->employment_type == 'intern')
                          Stagiaire
                        @else
                          -
                        @endif
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Taux horaire</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->hourly_rate ? number_format($user->hourly_rate, 2) . ' €' : '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Pays</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->country ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                <!-- Informations de connexion -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-sign-in-alt mr-2"></i> Informations de connexion
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-36 text-sm font-medium text-gray-500">Dernière connexion</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-36 text-sm font-medium text-gray-500">Dernière activité</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        {{ $user->last_activity_at ? $user->last_activity_at->diffForHumans() : 'Jamais' }}
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-36 text-sm font-medium text-gray-500">Dernière IP</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->last_ip ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-36 text-sm font-medium text-gray-500">Date d'inscription</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-36 text-sm font-medium text-gray-500">Double authentification</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        @if($user->two_factor_enabled)
                          <span class="text-green-600"><i class="las la-check-circle"></i> Activée</span>
                        @else
                          <span class="text-gray-500"><i class="las la-times-circle"></i> Désactivée</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Préférences -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-cog mr-2"></i> Préférences
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Langue</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        @if($user->language == 'fr')
                          Français
                        @elseif($user->language == 'en')
                          Anglais
                        @else
                          {{ $user->language ?? 'Français' }}
                        @endif
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Fuseau horaire</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->timezone ?? 'UTC' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Thème</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        @if($user->theme == 'dark')
                          Sombre
                        @else
                          Clair
                        @endif
                      </div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Notifications</div>
                      <div class="flex-1 text-gray-900 dark:text-white">
                        @if($user->preferences && isset($user->preferences['notifications']))
                          {{ $user->preferences['notifications'] ? 'Activées' : 'Désactivées' }}
                        @else
                          Activées
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Entreprise -->
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-building mr-2"></i> Entreprise
                  </h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Nom</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->company->name ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Email</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->company->email ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Téléphone</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->company->phone ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                      <div class="w-32 text-sm font-medium text-gray-500">Adresse</div>
                      <div class="flex-1 text-gray-900 dark:text-white">{{ $user->company->address ?? '-' }}</div>
                    </div>
                  </div>
                </div>

                <!-- Dernières activités (optionnel) -->
                @if(isset($recentActivities) && count($recentActivities) > 0)
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <i class="las la-history mr-2"></i> Dernières activités
                  </h4>
                  <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                      <div class="flex-shrink-0">
                        <i class="las la-clock text-gray-400 text-lg"></i>
                      </div>
                      <div class="ml-3 flex-1">
                        <p class="text-sm text-gray-900 dark:text-white">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
