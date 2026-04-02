@extends('layouts.app')

@section('title', 'Rapport Mensuel')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <div class="white-box">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="mb-2">Rapport Mensuel</h2>
                <p class="text-gray-500">{{ $startDate->format('F Y') }} ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})</p>
            </div>
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input type="month" name="month" value="{{ $month }}" class="border rounded-lg px-3 py-2">
                    <button type="submit" class="btn-primary px-4 py-2">Changer</button>
                </form>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="btn-secondary px-4 py-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </form>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="format" value="word">
                    <button type="submit" class="btn-secondary px-4 py-2">
                        <i class="fas fa-file-word"></i> Word
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Indicateurs clés -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="white-box p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Tâches totales</p>
                    <h3 class="text-2xl font-bold">{{ $data['summary']['tasks']['total'] }}</h3>
                </div>
                <i class="las la-tasks text-3xl text-blue-500"></i>
            </div>
        </div>
        <div class="white-box p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Taux complétion</p>
                    <h3 class="text-2xl font-bold">{{ $data['summary']['tasks']['completion_rate'] }}%</h3>
                </div>
                <i class="las la-chart-line text-3xl text-green-500"></i>
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

    <!-- Évolution mensuelle -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Évolution quotidienne</h4>
        <canvas id="monthlyChart" class="mt-4" style="height: 350px;"></canvas>
    </div>

    <!-- Top contributeurs -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Top contributeurs</h4>
        <div class="space-y-4 mt-4">
            @foreach($data['user_stats']->sortByDesc('tasks_completed')->take(5) as $stat)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ $stat['user']->avatar ?? asset('assets/images/default-avatar.png') }}" class="w-10 h-10 rounded-full">
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
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
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
            legend: { position: 'top' }
        },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Nombre de tâches' } }
        }
    }
});
</script>
@endpush
