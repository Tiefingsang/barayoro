@extends('layouts.app')

@section('title', 'Détails de la tâche')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails de la tâche</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('projects.index') }}">
                        <i class="las la-project-diagram shrink-0"></i>
                        <span>Projets</span>
                    </a>
                </li>
                @if($task->project)
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2" href="{{ route('projects.show', $task->project) }}">
                        <i class="las la-eye shrink-0"></i>
                        <span>{{ $task->project->name }}</span>
                    </a>
                </li>
                @endif
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

    <!-- Carte tâche -->
    <div class="white-box">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Code: {{ $task->code }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="las la-edit mr-1"></i> Modifier
                </a>
                <a href="{{ route('projects.show', $task->project) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <i class="las la-arrow-left mr-1"></i> Retour au projet
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">

            <!-- Colonne gauche - Statistiques -->
            <div class="col-span-12 lg:col-span-4">
                <div class="space-y-4">
                    <!-- Progression -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Progression</h4>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $task->progress }}%"></div>
                            </div>
                            <span class="text-lg font-bold">{{ $task->progress }}%</span>
                        </div>
                    </div>

                    <!-- Informations clés -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Informations</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Statut</span>
                                @php
                                    $statusColors = ['pending' => 'yellow', 'in_progress' => 'blue', 'review' => 'purple', 'completed' => 'green', 'cancelled' => 'red'];
                                    $statusLabels = ['pending' => 'En attente', 'in_progress' => 'En cours', 'review' => 'En révision', 'completed' => 'Terminé', 'cancelled' => 'Annulé'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $statusColors[$task->status] }}-100 text-{{ $statusColors[$task->status] }}-800">
                                    {{ $statusLabels[$task->status] }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Priorité</span>
                                @php
                                    $priorityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'orange', 'urgent' => 'red'];
                                    $priorityLabels = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $priorityColors[$task->priority] }}-100 text-{{ $priorityColors[$task->priority] }}-800">
                                    {{ $priorityLabels[$task->priority] }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Heures estimées</span>
                                <span>{{ $task->estimated_hours ?? '-' }} h</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Heures réelles</span>
                                <span>{{ $task->actual_hours ?? '-' }} h</span>
                            </div>
                            @if($task->start_date)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Date début</span>
                                <span>{{ $task->start_date->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            @if($task->due_date)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Date échéance</span>
                                <span class="{{ $task->isOverdue() ? 'text-red-600' : '' }}">{{ $task->due_date->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            @if($task->completed_at)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Date complétion</span>
                                <span>{{ $task->completed_at->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Détails -->
            <div class="col-span-12 lg:col-span-8">
                <div class="space-y-6">

                    <!-- Description -->
                    @if($task->description)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                    </div>
                    @endif

                    <!-- Assignation -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Assignation</h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="las la-user text-2xl text-blue-500"></i>
                                <div>
                                    <p class="font-medium">Assigné à</p>
                                    <p>{{ $task->assignee->name ?? 'Non assigné' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="las la-building text-2xl text-green-500"></i>
                                <div>
                                    <p class="font-medium">Département</p>
                                    <p>{{ $task->department->name ?? 'Non assigné' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="las la-user-check text-2xl text-purple-500"></i>
                                <div>
                                    <p class="font-medium">Créé par</p>
                                    <p>{{ $task->creator->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">le {{ $task->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Projet parent -->
                    @if($task->project)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Projet parent</h4>
                        <a href="{{ route('projects.show', $task->project) }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 transition">
                            <i class="las la-project-diagram text-2xl text-primary-300"></i>
                            <div>
                                <p class="font-medium">{{ $task->project->name }}</p>
                                <p class="text-sm text-gray-500">Code: {{ $task->project->code }}</p>
                            </div>
                        </a>
                    </div>
                    @endif

                    <!-- Sous-tâches -->
                    @if($subtasks && $subtasks->count() > 0)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Sous-tâches</h4>
                        <div class="space-y-2">
                            @foreach($subtasks as $subtask)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $subtask->title }}</p>
                                    <p class="text-xs text-gray-500">Assigné à {{ $subtask->assignee->name ?? 'Non assigné' }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($subtask->status == 'completed') bg-green-100 text-green-800
                                    @elseif($subtask->status == 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $subtask->status }}
                                </span>
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
