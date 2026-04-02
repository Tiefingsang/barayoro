@extends('layouts.app')

@section('title', 'Rapport Trimestriel')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- En-tête -->
    <div class="white-box">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="mb-2 text-2xl font-semibold">Rapport Trimestriel</h2>
                <p class="text-gray-500">
                    <i class="las la-calendar-alt"></i>
                    {{ $startDate->translatedFormat('F Y') }} - {{ $endDate->translatedFormat('F Y') }}
                    ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
                </p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <form method="GET" class="flex gap-2 items-center">
                    <select name="quarter" class="border rounded-lg px-3 py-2">
                        <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>1er Trimestre (Jan-Mar)</option>
                        <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>2ème Trimestre (Avr-Juin)</option>
                        <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>3ème Trimestre (Juil-Sep)</option>
                        <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>4ème Trimestre (Oct-Déc)</option>
                    </select>
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
                        <input type="hidden" name="quarter" value="{{ $quarter }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="btn-secondary px-4 py-2">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </form>
                    <form method="GET" class="inline">
                        <input type="hidden" name="quarter" value="{{ $quarter }}">
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

    <!-- Cartes KPI -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="white-box p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Tâches totales</p>
                    <h3 class="text-2xl font-bold">{{ $data['summary']['tasks']['total'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">sur le trimestre</p>
                </div>
                <i class="las la-tasks text-3xl text-blue-500"></i>
            </div>
        </div>
        <div class="white-box p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Taux de complétion</p>
                    <h3 class="text-2xl font-bold">{{ $data['summary']['tasks']['completion_rate'] }}%</h3>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $data['summary']['tasks']['completion_rate'] }}%"></div>
                    </div>
                </div>
                <i class="las la-check-circle text-3xl text-green-500"></i>
            </div>
        </div>
        <div class="white-box p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Chiffre d'affaires</p>
                    <h3 class="text-2xl font-bold">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</h3>
                </div>
                <i class="las la-money-bill text-3xl text-purple-500"></i>
            </div>
        </div>
        <div class="white-box p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Projets actifs</p>
                    <h3 class="text-2xl font-bold">{{ $data['summary']['projects']['active'] }}/{{ $data['summary']['projects']['total'] }}</h3>
                </div>
                <i class="las la-project-diagram text-3xl text-orange-500"></i>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution mensuelle -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Évolution mensuelle du trimestre</h4>
        <canvas id="quarterlyChart" class="mt-4" style="height: 350px;"></canvas>
    </div>

    <!-- Performance par projet -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Performance par projet</h4>
        <div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">Projet</th>
                        <th class="px-4 py-3 text-center">Tâches</th>
                        <th class="px-4 py-3 text-center">Terminées</th>
                        <th class="px-4 py-3 text-center">Progression</th>
                        <th class="px-4 py-3 text-right">Budget</th>
                        <th class="px-4 py-3 text-right">Réalisé</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['project_stats'] as $stat)
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
                        <td class="px-4 py-3 text-right">{{ number_format($stat['actual_cost'] ?? 0, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">Aucun projet pour ce trimestre</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top contributeurs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="white-box">
            <h4 class="bb-dashed-n30">Top contributeurs du trimestre</h4>
            <div class="space-y-4 mt-4">
                @php
                    $topUsers = collect($data['user_stats'])->sortByDesc('tasks_completed')->take(5);
                @endphp
                @forelse($topUsers as $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i class="las la-user text-xl text-primary-300"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $stat['user']->name }}</p>
                            <p class="text-sm text-gray-500">{{ $stat['user']->position ?? 'Employé' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">{{ $stat['tasks_completed'] }}</p>
                        <p class="text-sm text-gray-500">tâches terminées</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">Aucune donnée disponible</div>
                @endforelse
            </div>
        </div>

        <!-- Top clients -->
        <div class="white-box">
            <h4 class="bb-dashed-n30">Top clients du trimestre</h4>
            <div class="space-y-4 mt-4">
                @php
                    $topClients = collect($data['invoices'])->groupBy('client_id')->map(function($invoices) {
                        return [
                            'client' => $invoices->first()->client,
                            'total' => $invoices->sum('total')
                        ];
                    })->sortByDesc('total')->take(5);
                @endphp
                @forelse($topClients as $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="las la-building text-xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $stat['client']->name ?? 'Client' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">{{ number_format($stat['total'], 0, ',', ' ') }} FCFA</p>
                        <p class="text-sm text-gray-500">chiffre d'affaires</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">Aucune donnée disponible</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Résumé financier -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Résumé financier du trimestre</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <i class="las la-chart-line text-3xl text-green-500"></i>
                <p class="text-sm text-gray-500 mt-2">Chiffre d'affaires</p>
                <p class="text-xl font-bold text-green-600">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <i class="las la-credit-card text-3xl text-blue-500"></i>
                <p class="text-sm text-gray-500 mt-2">Factures payées</p>
                <p class="text-xl font-bold text-blue-600">{{ number_format($data['summary']['invoices']['paid_amount'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <i class="las la-clock text-3xl text-yellow-500"></i>
                <p class="text-sm text-gray-500 mt-2">Factures en attente</p>
                <p class="text-xl font-bold text-yellow-600">{{ number_format($data['summary']['invoices']['pending_amount'], 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Activités récentes du trimestre</h4>
        <div class="space-y-3 mt-4 max-h-96 overflow-y-auto">
            @forelse($data['activities'] as $activity)
            <div class="flex items-start gap-3 p-3 border-b hover:bg-gray-50">
                <i class="las la-bell text-xl text-primary-300"></i>
                <div class="flex-1">
                    <p class="text-sm">{{ $activity->description }}</p>
                    <div class="flex gap-3 mt-1">
                        <p class="text-xs text-gray-500">
                            <i class="las la-user"></i> {{ $activity->user->name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="las la-clock"></i> {{ $activity->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">Aucune activité pour ce trimestre</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('quarterlyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($data['chart_data']['labels']) !!},
        datasets: [
            {
                label: 'Tâches créées',
                data: {!! json_encode($data['chart_data']['tasks']) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 8
            },
            {
                label: 'Tâches terminées',
                data: {!! json_encode($data['chart_data']['completed']) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderRadius: 8
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
                ticks: { stepSize: 1 }
            },
            x: { title: { display: true, text: 'Mois' } }
        }
    }
});
</script>
@endpush
