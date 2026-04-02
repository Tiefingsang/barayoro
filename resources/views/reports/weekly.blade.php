@extends('layouts.app')

@section('title', 'Rapport Hebdomadaire')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <div class="white-box">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="mb-2">Rapport Hebdomadaire</h2>
                <p class="text-gray-500">Du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input type="week" name="week_start" value="{{ \Carbon\Carbon::parse($weekStart)->format('Y-\WW') }}" class="border rounded-lg px-3 py-2">
                    <button type="submit" class="btn-primary px-4 py-2">Changer</button>
                </form>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="week_start" value="{{ $weekStart }}">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="btn-secondary px-4 py-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </form>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="week_start" value="{{ $weekStart }}">
                    <input type="hidden" name="format" value="word">
                    <button type="submit" class="btn-secondary px-4 py-2">
                        <i class="fas fa-file-word"></i> Word
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Évolution quotidienne</h4>
        <canvas id="weeklyChart" class="mt-4" style="height: 300px;"></canvas>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="white-box p-4 text-center">
            <i class="las la-tasks text-3xl text-blue-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['tasks']['total'] }}</h3>
            <p class="text-gray-500">Tâches totales</p>
        </div>
        <div class="white-box p-4 text-center">
            <i class="las la-project-diagram text-3xl text-green-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['projects']['active'] }}/{{ $data['summary']['projects']['total'] }}</h3>
            <p class="text-gray-500">Projets actifs/total</p>
        </div>
        <div class="white-box p-4 text-center">
            <i class="las la-chart-line text-3xl text-purple-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ $data['summary']['tasks']['completion_rate'] }}%</h3>
            <p class="text-gray-500">Taux de complétion</p>
        </div>
        <div class="white-box p-4 text-center">
            <i class="las la-money-bill text-3xl text-orange-500"></i>
            <h3 class="text-2xl font-bold mt-2">{{ number_format($data['summary']['invoices']['total_amount'], 0, ',', ' ') }} FCFA</h3>
            <p class="text-gray-500">Chiffre d'affaires</p>
        </div>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['project_stats'] as $stat)
                    <tr class="border-b">
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('weeklyChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($data['chart_data']['labels']) !!},
        datasets: [
            {
                label: 'Tâches créées',
                data: {!! json_encode($data['chart_data']['tasks']) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Tâches terminées',
                data: {!! json_encode($data['chart_data']['completed']) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});
</script>
@endpush
