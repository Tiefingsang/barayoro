@extends('layouts.app')

@section('title', 'Détails du département')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails du département</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('departments.index') }}">
                        <i class="las la-building shrink-0"></i>
                        <span>Départements</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2 text-primary-300" href="#">
                        <i class="las la-eye shrink-0"></i>
                        <span>Détails</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Carte département -->
    <div class="white-box">
        <div class="grid grid-cols-12 gap-4 lg:gap-6">

            <!-- Colonne gauche -->
            <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-4">
                        <div class="size-32 lg:size-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <i class="las la-building text-5xl text-white"></i>
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                            @if($department->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactif</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $department->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $department->code }}</p>

                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-3 gap-2 w-full mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Employés</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_teams'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Équipes</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_projects'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Projets</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 mt-6 w-full">
                        <a href="{{ route('departments.edit', $department) }}" class="flex-1 px-4 py-2 text-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="las la-edit mr-1"></i> Modifier
                        </a>
                        <a href="{{ route('departments.index') }}" class="flex-1 px-4 py-2 text-center border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                            <i class="las la-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Informations -->
            <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
                <div class="space-y-6">

                    <!-- Informations générales -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="las la-info-circle mr-2"></i> Informations générales
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-500">Code</div>
                                <div class="flex-1 text-gray-900 dark:text-white">{{ $department->code }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-500">Nom</div>
                                <div class="flex-1 text-gray-900 dark:text-white">{{ $department->name }}</div>
                            </div>
                            <div class="flex items-start col-span-2">
                                <div class="w-32 text-sm font-medium text-gray-500">Description</div>
                                <div class="flex-1 text-gray-900 dark:text-white">{{ $department->description ?? '-' }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-500">Manager</div>
                                <div class="flex-1">
                                    @if($department->manager)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $department->manager->avatar_url }}" class="w-6 h-6 rounded-full">
                                            <span class="text-gray-900 dark:text-white">{{ $department->manager->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-500">Non assigné</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-500">Créé le</div>
                                <div class="flex-1 text-gray-900 dark:text-white">{{ $department->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-500">Dernière modification</div>
                                <div class="flex-1 text-gray-900 dark:text-white">{{ $department->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des employés -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="las la-users mr-2"></i> Employés du département
                        </h4>
                        <div class="space-y-2">
                            @forelse($department->users as $employee)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $employee->avatar_url }}" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $employee->position ?? 'Employé' }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('users.show', $employee) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="las la-eye"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4 text-gray-500">
                                Aucun employé dans ce département.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
