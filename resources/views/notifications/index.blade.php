@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
<div :class="[$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto xxl:px-0 xxl:pt-8':'',$store.app.stretch?'xxxl:max-w-[92%] mx-auto':'']" class="p-3 md:p-4 xxl:p-6 space-y-4 xxl:space-y-6">

    <!-- Breadcrumb -->
    <div class="white-box xxxl:p-6">
        <div class="n20-box xxxl:p-6 relative ltr:bg-right rtl:bg-left bg-no-repeat max-[650px]:!bg-none bg-contain" style="background-image: url({{ asset('assets/images/breadcrumb-el-1.png') }})">
            <h2 class="mb-3 xxxl:mb-5">Mes notifications</h2>
            <ul class="flex flex-wrap gap-2 items-center">
                <li>
                    <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
                        <i class="las la-home shrink-0"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="text-sm text-neutral-100">•</li>
                <li>
                    <a class="flex items-center gap-2 text-primary-300" href="#">
                        <i class="las la-bell shrink-0"></i>
                        <span>Notifications</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-12 gap-4 xxl:gap-6">
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-bell text-4xl text-blue-500"></i>
                <h3 class="mt-2">{{ $stats['total'] }}</h3>
                <p class="text-gray-500">Total notifications</p>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-envelope text-4xl text-yellow-500"></i>
                <h3 class="mt-2">{{ $stats['unread'] }}</h3>
                <p class="text-gray-500">Non lues</p>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-shopping-cart text-4xl text-purple-500"></i>
                <h3 class="mt-2">{{ $stats['by_type']->where('type', 'order')->sum('count') }}</h3>
                <p class="text-gray-500">Commandes</p>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 lg:col-span-3">
            <div class="white-box text-center">
                <i class="las la-file-invoice text-4xl text-green-500"></i>
                <h3 class="mt-2">{{ $stats['by_type']->where('type', 'invoice')->sum('count') }}</h3>
                <p class="text-gray-500">Factures</p>
            </div>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="white-box">
        <div class="flex flex-wrap gap-4 justify-between items-center bb-dashed-n30 pb-4">
            <h4>Historique des notifications</h4>
            <div class="flex flex-wrap items-center gap-4">
                @if($stats['unread'] > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary-outlined py-2">
                        <i class="las la-check-double text-sm"></i> Tout marquer comme lu
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Filtres -->
        <div class="flex flex-wrap gap-4 mb-6 mt-4">
            <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-4">
                <select name="type" class="border rounded-lg px-4 py-2">
                    <option value="">Tous les types</option>
                    <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Commandes</option>
                    <option value="order_status" {{ request('type') == 'order_status' ? 'selected' : '' }}>Statut commande</option>
                    <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Factures</option>
                    <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Paiements</option>
                    <option value="project" {{ request('type') == 'project' ? 'selected' : '' }}>Projets</option>
                    <option value="task" {{ request('type') == 'task' ? 'selected' : '' }}>Tâches</option>
                    <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Clients</option>
                    <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Alertes</option>
                </select>
                <select name="read" class="border rounded-lg px-4 py-2">
                    <option value="">Tous</option>
                    <option value="unread" {{ request('read') == 'unread' ? 'selected' : '' }}>Non lues</option>
                    <option value="read" {{ request('read') == 'read' ? 'selected' : '' }}>Lues</option>
                </select>
                <button type="submit" class="btn-primary px-4 py-2">Filtrer</button>
                <a href="{{ route('notifications.index') }}" class="btn-secondary px-4 py-2">Réinitialiser</a>
            </form>
        </div>

        <!-- Liste -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
            <div class="flex items-start gap-4 p-4 rounded-lg border transition-all duration-200 hover:shadow-md
                {{ !$notification->is_read ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800' : 'bg-white border-gray-200 dark:bg-neutral-904 dark:border-neutral-700' }}">

                <!-- Icône -->
                <div class="shrink-0">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center
                        @if($notification->type == 'order') bg-blue-100 text-blue-600
                        @elseif($notification->type == 'order_status') bg-purple-100 text-purple-600
                        @elseif($notification->type == 'invoice') bg-green-100 text-green-600
                        @elseif($notification->type == 'payment') bg-teal-100 text-teal-600
                        @elseif($notification->type == 'warning') bg-red-100 text-red-600
                        @elseif($notification->type == 'project') bg-indigo-100 text-indigo-600
                        @elseif($notification->type == 'task') bg-orange-100 text-orange-600
                        @elseif($notification->type == 'customer') bg-pink-100 text-pink-600
                        @else bg-gray-100 text-gray-600
                        @endif">
                        <i class="text-2xl
                            @if($notification->type == 'order') las la-shopping-cart
                            @elseif($notification->type == 'order_status') las la-exchange-alt
                            @elseif($notification->type == 'invoice') las la-file-invoice
                            @elseif($notification->type == 'payment') las la-credit-card
                            @elseif($notification->type == 'warning') las la-exclamation-triangle
                            @elseif($notification->type == 'project') las la-project-diagram
                            @elseif($notification->type == 'task') las la-tasks
                            @elseif($notification->type == 'customer') las la-user
                            @else las la-bell
                            @endif"></i>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap justify-between items-start gap-2">
                        <div>
                            <h5 class="font-semibold {{ !$notification->is_read ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                                {{ $notification->title }}
                            </h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                            @if(!$notification->is_read)
                                <span class="ml-2 inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-3 mt-3">
                        @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="text-sm text-primary-300 hover:underline inline-flex items-center gap-1">
                            <i class="las la-external-link-alt"></i> Voir les détails
                        </a>
                        @endif

                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 inline-flex items-center gap-1">
                                <i class="las la-check-circle"></i> Marquer comme lu
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-500">
                <i class="las la-bell-slash text-5xl mb-3 block"></i>
                <p class="text-lg">Aucune notification</p>
                <p class="text-sm mt-1">Les notifications apparaîtront ici lorsque vous recevrez des mises à jour</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Rafraîchir le compteur de notifications toutes les 30 secondes
    setInterval(function() {
        fetch('{{ route("notifications.recent") }}')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour le badge dans la navbar
                const badge = document.querySelector('#notification-badge');
                if (badge) {
                    if (data.unread_count > 0) {
                        badge.textContent = data.unread_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Erreur:', error));
    }, 30000);
</script>
@endpush
