@extends('layouts.app')

@section('title', 'Rapport Annuel')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- En-tête -->
    <div class="white-box">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="mb-2 text-2xl font-semibold">Rapport Annuel</h2>
                <p class="text-gray-500">
                    <i class="las la-calendar-alt"></i>
                    Année {{ $year }} ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
                </p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <form method="GET" class="flex gap-2">
                    <select name="year" class="border rounded-lg px-3 py-2">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn-primary px-4 py-2">
                        <i class="las la-chart-line"></i> Générer
                    </button>
                </form>
                <div class="flex gap-2">
                    <form method="GET" class="inline">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="btn-secondary px-4 py-2">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </form>
                    <form method="GET" class="inline">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="format" value="word">
                        <button type="submit" class="btn-secondary px-4 py-2">
                            <i class="fas fa-file-word"></i> Word
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes KPI Annuels -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="white-box p-4 bg-gradient-to-r from-blue-50 to-blue-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Tâches totales</p>
                    <h3 class="text-3xl font-bold text-blue-700">{{ $data['summary']['tasks']['total'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">sur l'année</p>
                </div>
                <i class="las la-tasks text-4xl text-blue-500"></i>
            </div>
        </div>
        <div class="white-box p-4 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Taux de complétion</p>
                    <h3 class="text-3xl font-bold text-green-700">{{ $data['summary']['tasks']['completion_rate'] }}%</h3>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $data['summary']['tasks']['completion_rate'] }}%"></div>
                    </div>
                </div>
                <i class="las la-check-circle text-4xl text-green-500"></i>
            </div>
        </div>
        <div class="white-box p-4 bg-gradient-to-r from-purple-50 to-purple-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Chiffre d'affaires</p>
                    <h3 class="text-3xl font-bold text-purple-700">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</h3>
                </div>
                <i class="las la-money-bill text-4xl text-purple-500"></i>
            </div>
        </div>
        <div class="white-box p-4 bg-gradient-to-r from-orange-50 to-orange-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm">Projets réalisés</p>
                    <h3 class="text-3xl font-bold text-orange-700">{{ $data['summary']['projects']['completed'] }}/{{ $data['summary']['projects']['total'] }}</h3>
                </div>
                <i class="las la-project-diagram text-4xl text-orange-500"></i>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution annuelle -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Évolution mensuelle sur l'année</h4>
        <canvas id="annualChart" class="mt-4" style="height: 400px;"></canvas>
    </div>

    <!-- Statistiques clés -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="white-box">
            <h4 class="bb-dashed-n30">Résumé annuel</h4>
            <div class="space-y-3 mt-4">
                <div class="flex justify-between items-center p-2 border-b">
                    <span class="text-gray-600">📊 Moyenne tâches/mois</span>
                    <span class="font-semibold">{{ round($data['summary']['tasks']['total'] / 12) }}</span>
                </div>
                <div class="flex justify-between items-center p-2 border-b">
                    <span class="text-gray-600">✅ Tâches terminées/mois</span>
                    <span class="font-semibold text-green-600">{{ round($data['summary']['tasks']['completed'] / 12) }}</span>
                </div>
                <div class="flex justify-between items-center p-2 border-b">
                    <span class="text-gray-600">💰 CA moyen/mois</span>
                    <span class="font-semibold text-purple-600">{{ number_format($data['summary']['invoices']['total_amount'] / 12, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center p-2 border-b">
                    <span class="text-gray-600">👥 Utilisateurs actifs</span>
                    <span class="font-semibold">{{ $data['summary']['users']['active'] }}</span>
                </div>
                <div class="flex justify-between items-center p-2">
                    <span class="text-gray-600">📈 Taux de croissance annuel</span>
                    <span class="font-semibold text-blue-600">{{ $data['summary']['tasks']['completion_rate'] }}%</span>
                </div>
            </div>
        </div>

        <div class="white-box">
            <h4 class="bb-dashed-n30">Top 5 contributeurs de l'année</h4>
            <div class="space-y-4 mt-4">
                @php
                    $topUsers = collect($data['user_stats'])->sortByDesc('tasks_completed')->take(5);
                @endphp
                @forelse($topUsers as $index => $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $index == 0 ? 'bg-yellow-100' : ($index == 1 ? 'bg-gray-100' : ($index == 2 ? 'bg-orange-100' : 'bg-blue-100')) }} flex items-center justify-center">
                            <span class="font-bold {{ $index == 0 ? 'text-yellow-600' : ($index == 1 ? 'text-gray-600' : ($index == 2 ? 'text-orange-600' : 'text-blue-600')) }}">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium">{{ $stat['user']->name }}</p>
                            <p class="text-xs text-gray-500">{{ $stat['tasks_completed'] }} tâches terminées</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">{{ $stat['completion_rate'] }}%</p>
                        <p class="text-xs text-gray-500">taux de réussite</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">Aucune donnée disponible</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Performance par trimestre -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Performance par trimestre</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            @php
                $quarterlyTasks = [
                    1 => ['name' => 'Q1', 'color' => 'blue'],
                    2 => ['name' => 'Q2', 'color' => 'green'],
                    3 => ['name' => 'Q3', 'color' => 'orange'],
                    4 => ['name' => 'Q4', 'color' => 'purple']
                ];
                $totalTasks = $data['summary']['tasks']['total'];
            @endphp
            @foreach($quarterlyTasks as $q => $info)
                @php
                    $quarterTasks = $data['tasks']->filter(function($task) use ($q) {
                        return ceil($task->created_at->month / 3) == $q;
                    })->count();
                    $percentage = $totalTasks > 0 ? round(($quarterTasks / $totalTasks) * 100) : 0;
                @endphp
                <div class="text-center p-4 rounded-lg border bg-{{ $info['color'] }}-50">
                    <p class="font-bold text-lg text-{{ $info['color'] }}-600">{{ $info['name'] }}</p>
                    <p class="text-2xl font-bold mt-2">{{ $quarterTasks }}</p>
                    <p class="text-sm text-gray-500">tâches</p>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div class="bg-{{ $info['color'] }}-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $percentage }}% du total</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Projets majeurs de l'année -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Projets majeurs de l'année</h4>
        <div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">Projet</th>
                        <th class="px-4 py-3 text-center">Tâches</th>
                        <th class="px-4 py-3 text-center">Terminées</th>
                        <th class="px-4 py-3 text-center">Progression</th>
                        <th class="px-4 py-3 text-right">Budget</th>
                        <th class="px-4 py-3 text-right">CA généré</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $topProjects = collect($data['project_stats'])->sortByDesc('total_tasks')->take(10);
                    @endphp
                    @forelse($topProjects as $stat)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $stat['project']->name }}</td>
                        <td class="px-4 py-3 text-center">{{ $stat['total_tasks'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $stat['tasks_completed'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stat['progress'] }}%"></div>
                                </div>
                                <span class="text-sm">{{ $stat['progress'] }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">{{ number_format($stat['budget'] ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-3 text-right text-green-600">{{ number_format(($stat['budget'] ?? 0) * ($stat['progress'] / 100), 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">Aucun projet pour cette année</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bilan de l'année -->
    <div class="white-box bg-gradient-to-r from-gray-50 to-gray-100">
        <h4 class="bb-dashed-n30 text-center">🏆 Bilan de l'année {{ $year }}</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4 text-center">
            <div>
                <div class="text-4xl mb-2">📊</div>
                <p class="text-2xl font-bold text-blue-600">{{ $data['summary']['tasks']['total'] }}</p>
                <p class="text-sm text-gray-600">Tâches traitées</p>
            </div>
            <div>
                <div class="text-4xl mb-2">✅</div>
                <p class="text-2xl font-bold text-green-600">{{ $data['summary']['tasks']['completed'] }}</p>
                <p class="text-sm text-gray-600">Tâches terminées</p>
            </div>
            <div>
                <div class="text-4xl mb-2">💰</div>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</p>
                <p class="text-sm text-gray-600">Chiffre d'affaires</p>
            </div>
        </div>
        <div class="text-center mt-6 pt-4 border-t">
            <p class="text-gray-500">
                Rapport généré le {{ now()->format('d/m/Y à H:i') }} par {{ Auth::user()->name }}
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('annualChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [
            {
                label: 'Tâches créées',
                data: {!! json_encode($data['chart_data']['tasks']) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Tâches terminées',
                data: {!! json_encode($data['chart_data']['completed']) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.raw + ' tâches';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Nombre de tâches' },
                ticks: { stepSize: 5 }
            },
            x: { title: { display: true, text: 'Mois de l\'année' } }
        }
    }
});
</script>
@endpush
