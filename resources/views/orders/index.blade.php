@extends('layouts.app')

@section('title', 'Gestion des commandes')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Breadcrumb -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Gestion des commandes</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li><a class="flex items-center gap-2" href="{{ route('dashboard') }}"><i class="las la-home shrink-0"></i><span>Accueil</span></a></li>
                <li class="text-sm text-neutral-100">•</li>
                <li><a class="flex items-center gap-2 text-primary-300" href="#"><i class="las la-shopping-cart shrink-0"></i><span>Commandes</span></a></li>
            </ul>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-12 gap-4 xxl:gap-6">
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-shopping-cart text-4xl text-blue-500"></i>
                <h3 class="mt-2">{{ $statusCounts['total'] }}</h3>
                <p class="text-gray-500">Total commandes</p>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-clock text-4xl text-yellow-500"></i>
                <h3 class="mt-2">{{ $statusCounts['pending'] }}</h3>
                <p class="text-gray-500">En attente</p>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-spinner text-4xl text-orange-500"></i>
                <h3 class="mt-2">{{ $statusCounts['processing'] }}</h3>
                <p class="text-gray-500">En traitement</p>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-check-circle text-4xl text-green-500"></i>
                <h3 class="mt-2">{{ $statusCounts['delivered'] }}</h3>
                <p class="text-gray-500">Livrées</p>
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="white-box">
        <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30">
            <h4>Liste des commandes</h4>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('orders.create') }}" class="btn-primary-outlined py-2">
                    <i class="las la-plus-circle text-sm"></i> Nouvelle commande
                </a>
                <a href="{{ route('orders.export') }}" class="btn-primary-outlined py-2">
                    <i class="las la-download text-sm"></i> Exporter
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="flex flex-wrap gap-4 mb-6">
            <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="border rounded-lg px-4 py-2">
                <select name="status" class="border rounded-lg px-4 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Date début" class="border rounded-lg px-4 py-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Date fin" class="border rounded-lg px-4 py-2">
                <button type="submit" class="btn-primary px-4 py-2">Filtrer</button>
                <a href="{{ route('orders.index') }}" class="btn-secondary px-4 py-2">Réinitialiser</a>
            </form>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-neutral-20 dark:bg-neutral-903">
                        <th class="px-6 py-4">N° Commande</th>
                        <th class="px-6 py-4">Client</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4">Paiement</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b hover:bg-neutral-20">
                        <td class="px-6 py-3">
                            <span class="font-mono text-sm">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-3">{{ $order->client->name }}</td>
                        <td class="px-6 py-3">{{ $order->order_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 font-semibold">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->payment_status === 'paid' ? 'green' : ($order->payment_status === 'partial' ? 'yellow' : 'gray') }}-100">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                    <i class="las la-eye text-xl"></i>
                                </a>
                                @if($order->canBeModified())
                                <a href="{{ route('orders.edit', $order) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                                    <i class="las la-pen text-xl"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <i class="las la-shopping-cart text-4xl mb-2 block"></i>
                            Aucune commande trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
