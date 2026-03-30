@extends('layouts.app')

@section('title', 'Détails du projet')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Fil d'Ariane -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Détails du projet</h2>
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

    <!-- Carte projet -->
    <div class="white-box">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Code: {{ $project->code }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="las la-edit mr-1"></i> Modifier
                </a>
                <a href="{{ route('projects.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <i class="las la-arrow-left mr-1"></i> Retour
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
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span class="text-lg font-bold">{{ $project->progress }}%</span>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $stats['total_tasks'] }}</p>
                            <p class="text-xs text-gray-500">Tâches totales</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['completed_tasks'] }}</p>
                            <p class="text-xs text-gray-500">Tâches terminées</p>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress_tasks'] }}</p>
                            <p class="text-xs text-gray-500">En cours</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_time'] / 60, 1) }}</p>
                            <p class="text-xs text-gray-500">Heures travaillées</p>
                        </div>
                    </div>

                    <!-- Informations clés -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Informations clés</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Statut</span>
                                @php
                                    $statusColors = ['draft' => 'gray', 'planned' => 'blue', 'in_progress' => 'orange', 'on_hold' => 'yellow', 'completed' => 'green', 'cancelled' => 'red'];
                                    $statusLabels = ['draft' => 'Brouillon', 'planned' => 'Planifié', 'in_progress' => 'En cours', 'on_hold' => 'En attente', 'completed' => 'Terminé', 'cancelled' => 'Annulé'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $statusColors[$project->status] }}-100 text-{{ $statusColors[$project->status] }}-800">
                                    {{ $statusLabels[$project->status] }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Priorité</span>
                                @php
                                    $priorityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'orange', 'critical' => 'red'];
                                    $priorityLabels = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'critical' => 'Critique'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $priorityColors[$project->priority] }}-100 text-{{ $priorityColors[$project->priority] }}-800">
                                    {{ $priorityLabels[$project->priority] }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Budget</span>
                                <span class="font-medium">{{ number_format($project->budget, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Coût réel</span>
                                <span class="font-medium">{{ number_format($project->actual_cost, 0, ',', ' ') }} FCFA</span>
                            </div>
                            @if($project->start_date)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Date début</span>
                                <span>{{ $project->start_date->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            @if($project->due_date)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Date fin</span>
                                <span class="{{ $project->isOverdue() ? 'text-red-600' : '' }}">{{ $project->due_date->format('d/m/Y') }}</span>
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
                    @if($project->description)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $project->description }}</p>
                    </div>
                    @endif

                    <!-- Client -->
                    @if($project->client)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Client</h4>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <i class="las la-building text-2xl text-gray-400"></i>
                            <div>
                                <p class="font-medium">{{ $project->client->name }}</p>
                                <p class="text-sm text-gray-500">{{ $project->client->email }}</p>
                                <p class="text-sm text-gray-500">{{ $project->client->phone }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Équipe -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Équipe</h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="las la-user-tie text-2xl text-blue-500"></i>
                                <div>
                                    <p class="font-medium">Chef de projet</p>
                                    <p>{{ $project->manager->name ?? 'Non assigné' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="las la-building text-2xl text-green-500"></i>
                                <div>
                                    <p class="font-medium">Département</p>
                                    <p>{{ $project->department->name ?? 'Non assigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($project->tags && count($project->tags) > 0)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tags</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->tags as $tag)
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tâches du projet -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Tâches du projet</h4>
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn-primary-outlined text-sm py-1 px-3">
                    <i class="las la-plus-circle"></i> Ajouter une tâche
                </a>
            </div>

            @if($project->tasks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Titre</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Assigné à</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Priorité</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Échéance</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($project->tasks as $task)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3 text-sm font-mono">{{ $task->code }}</td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium">{{ Str::limit($task->title, 50) }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($task->description, 40) }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($task->assignee)
                                        <img src="{{ $task->assignee->avatar_url }}" class="w-6 h-6 rounded-full">
                                        <span class="text-sm">{{ $task->assignee->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">Non assigné</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = ['pending' => 'yellow', 'in_progress' => 'blue', 'review' => 'purple', 'completed' => 'green', 'cancelled' => 'red'];
                                    $statusLabels = ['pending' => 'En attente', 'in_progress' => 'En cours', 'review' => 'En révision', 'completed' => 'Terminé', 'cancelled' => 'Annulé'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $statusColors[$task->status] }}-100 text-{{ $statusColors[$task->status] }}-800">
                                    {{ $statusLabels[$task->status] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $priorityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'orange', 'urgent' => 'red'];
                                    $priorityLabels = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $priorityColors[$task->priority] }}-100 text-{{ $priorityColors[$task->priority] }}-800">
                                    {{ $priorityLabels[$task->priority] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm {{ $task->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                        <i class="las la-eye text-lg"></i>
                                    </a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                                        <i class="las la-pen text-lg"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette tâche ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                            <i class="las la-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="las la-tasks text-4xl mb-2 block"></i>
                <p>Aucune tâche pour ce projet.</p>
                <div class="mt-2">
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="text-primary-300 hover:underline">
                        Créer la première tâche
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
