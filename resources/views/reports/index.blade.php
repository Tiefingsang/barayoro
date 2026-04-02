@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative">
            <h2 class="mb-3 xxxl:mb-5">Rapports d'activité</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li><a href="{{ route('dashboard') }}"><i class="las la-home"></i> Accueil</a></li>
                <li class="text-neutral-100">•</li>
                <li><a href="#" class="text-primary-300">Rapports</a></li>
            </ul>
        </div>
    </div>

    <!-- Cartes des types de rapports -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <a href="{{ route('reports.daily') }}" class="white-box p-4 text-center hover:shadow-lg transition">
            <i class="las la-calendar-day text-4xl text-blue-500"></i>
            <h3 class="mt-2 text-lg font-semibold">Journalier</h3>
            <p class="text-sm text-gray-500">Rapport quotidien</p>
        </a>
        <a href="{{ route('reports.weekly') }}" class="white-box p-4 text-center hover:shadow-lg transition">
            <i class="las la-calendar-week text-4xl text-green-500"></i>
            <h3 class="mt-2 text-lg font-semibold">Hebdomadaire</h3>
            <p class="text-sm text-gray-500">Rapport de la semaine</p>
        </a>
        <a href="{{ route('reports.monthly') }}" class="white-box p-4 text-center hover:shadow-lg transition">
            <i class="las la-calendar-alt text-4xl text-purple-500"></i>
            <h3 class="mt-2 text-lg font-semibold">Mensuel</h3>
            <p class="text-sm text-gray-500">Rapport du mois</p>
        </a>
        <a href="{{ route('reports.quarterly') }}" class="white-box p-4 text-center hover:shadow-lg transition">
            <i class="las la-chart-line text-4xl text-orange-500"></i>
            <h3 class="mt-2 text-lg font-semibold">Trimestriel</h3>
            <p class="text-sm text-gray-500">Rapport du trimestre</p>
        </a>
        <a href="{{ route('reports.annual') }}" class="white-box p-4 text-center hover:shadow-lg transition">
            <i class="las la-chart-bar text-4xl text-red-500"></i>
            <h3 class="mt-2 text-lg font-semibold">Annuel</h3>
            <p class="text-sm text-gray-500">Rapport de l'année</p>
        </a>
    </div>

    <!-- Rapports sauvegardés -->
    <div class="white-box">
        <h4 class="bb-dashed-n30">Rapports générés</h4>
        <div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Format</th>
                        <th class="px-4 py-3 text-left">Généré le</th>
                        <th class="px-4 py-3 text-center">Téléchargements</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $report->name }}</td>
                        <td class="px-4 py-3">
                            @php
                                $types = ['daily' => 'Journalier', 'weekly' => 'Hebdomadaire', 'monthly' => 'Mensuel', 'quarterly' => 'Trimestriel', 'annual' => 'Annuel'];
                            @endphp
                            {{ $types[$report->type] ?? ucfirst($report->type) }}
                        </td>
                        <td class="px-4 py-3">{{ strtoupper($report->format) }}</td>
                        <td class="px-4 py-3">{{ $report->generated_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">{{ $report->download_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('reports.download', $report) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce rapport ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="las la-file-alt text-4xl mb-2 block"></i>
                            Aucun rapport généré pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
