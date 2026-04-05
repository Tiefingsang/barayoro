@extends('layouts.app')

@section('title', 'Analytiques')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Tableau de bord analytique</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Tâches totales</div>
            <div class="text-3xl font-bold">{{ $stats['total_tasks'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-green-500 text-sm">Tâches terminées</div>
            <div class="text-3xl font-bold">{{ $stats['completed_tasks'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-blue-500 text-sm">Projets</div>
            <div class="text-3xl font-bold">{{ $stats['total_projects'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-purple-500 text-sm">Clients</div>
            <div class="text-3xl font-bold">{{ $stats['total_clients'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Factures</h2>
            <p class="text-gray-600">Total factures: {{ $stats['total_invoices'] }}</p>
            <p class="text-green-600">Chiffre d'affaires: {{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Progression</h2>
            <div class="mb-2 flex justify-between">
                <span>Tâches</span>
                <span>{{ round(($stats['completed_tasks'] / max($stats['total_tasks'], 1)) * 100) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: {{ round(($stats['completed_tasks'] / max($stats['total_tasks'], 1)) * 100) }}%"></div>
            </div>
            <div class="mt-4 mb-2 flex justify-between">
                <span>Projets</span>
                <span>{{ round(($stats['completed_projects'] / max($stats['total_projects'], 1)) * 100) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ round(($stats['completed_projects'] / max($stats['total_projects'], 1)) * 100) }}%"></div>
            </div>
        </div>
    </div>
</div>
@endsection
