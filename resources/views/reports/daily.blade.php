@extends('layouts.app')

@section('title', 'Rapport Journalier')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <div class="white-box">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="mb-2">Rapport Journalier</h2>
                <p class="text-gray-500">Date: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="border rounded-lg px-3 py-2">
                    <button type="submit" class="btn-primary px-4 py-2">Changer</button>
                </form>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="btn-secondary px-4 py-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </form>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="format" value="word">
                    <button type="submit" class="btn-secondary px-4 py-2">
                        <i class="fas fa-file-word"></i> Word
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="white-box p-4 text-center">
            <i class="las la-tasks text-3xl text-blue-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['tasks']['total'] }}</h3>
            <p class="text-gray-500">Tâches totales</p>
        </div>
        <div class="white-box p-4 text-center">
            <i class="las la-check-circle text-3xl text-green-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['tasks']['completed'] }}</h3>
            <p class="text-gray-500">Tâches terminées</p>
        </div>
        <div class="white-box p-4 text-center">
            <i class="las la-chart-line text-3xl text-purple-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['tasks']['completion_rate'] }}%</h3>
            <p class="text-gray-500">Taux de complétion</p>
        </div>
        <div class="white-box p-4 text-center">
            <i class="las la-users text-3xl text-orange-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['users']['with_tasks'] }}</h3>
            <p class="text-gray-500">Utilisateurs actifs</p>
        </div>
    </div>

    <!-- Performance par utilisateur -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Performance par utilisateur</h4>
        <div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">Utilisateur</th>
                        <th class="px-4 py-3 text-center">Tâches totales</th>
                        <th class="px-4 py-3 text-center">Terminées</th>
                        <th class="px-4 py-3 text-center">En cours</th>
                        <th class="px-4 py-3 text-center">Taux</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['user_stats'] as $stat)
                    <tr class="border-b">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $stat['user']->avatar ?? asset('assets/images/default-avatar.png') }}" class="w-8 h-8 rounded-full">
                                <span>{{ $stat['user']->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">{{ $stat['total_tasks'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $stat['tasks_completed'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $stat['tasks_in_progress'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stat['completion_rate'] }}%"></div>
                                </div>
                                <span>{{ $stat['completion_rate'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Liste des tâches -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Tâches du jour</h4>
        <div class="space-y-3 mt-4">
            @forelse($data['tasks'] as $task)
            <div class="flex justify-between items-center p-3 border rounded-lg hover:bg-gray-50">
                <div>
                    <p class="font-medium">{{ $task->title }}</p>
                    <p class="text-sm text-gray-500">Projet: {{ $task->project->name ?? 'Sans projet' }} | Assigné à: {{ $task->assignee->name ?? 'Non assigné' }}</p>
                </div>
                <div>
                    @php
                        $statusColors = ['pending' => 'gray', 'in_progress' => 'blue', 'completed' => 'green'];
                        $statusLabels = ['pending' => 'En attente', 'in_progress' => 'En cours', 'completed' => 'Terminé'];
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $statusColors[$task->status] }}-100 text-{{ $statusColors[$task->status] }}-800">
                        {{ $statusLabels[$task->status] }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">Aucune tâche pour cette période</p>
            @endforelse
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Activités récentes</h4>
        <div class="space-y-3 mt-4">
            @forelse($data['activities'] as $activity)
            <div class="flex items-start gap-3 p-2">
                <i class="las la-bell text-xl text-primary-300"></i>
                <div>
                    <p class="text-sm">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->format('H:i') }} - {{ $activity->user->name }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">Aucune activité récente</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
