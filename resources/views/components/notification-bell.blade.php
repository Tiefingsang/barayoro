@php
    $unreadCount = Auth::check() ? App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() : 0;
    $recentNotifications = Auth::check() ? App\Models\Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(5)->get() : collect();
@endphp

<div x-data="{
    open: false,
    unreadCount: {{ $unreadCount }},
    notifications: {{ json_encode($recentNotifications) }},
    fetchNotifications() {
        fetch('{{ route("notifications.recent") }}')
            .then(response => response.json())
            .then(data => {
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
                // Mettre à jour le badge
                const badge = document.querySelector('#notification-badge');
                if (badge) {
                    if (this.unreadCount > 0) {
                        badge.textContent = this.unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            });
    },
    markAsRead(notificationId, event) {
        event.preventDefault();
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            this.fetchNotifications();
        });
    }
}"
@click.away="open = false"
class="relative">

    <!-- Bouton de notification -->
    <button
        title="Notifications"
        @click="open = !open; if(!open) fetchNotifications()"
        :class="$store.app.menu=='horizontal'?'bg-neutral-0 dark:bg-neutral-903':'bg-neutral-20 dark:bg-neutral-903'"
        class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500 relative"
    >
        <i class="las la-bell"></i>

        <!-- Badge de notification -->
        <span
            id="notification-badge"
            x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute -top-1 -right-1 size-5 text-xs f-center text-neutral-0 bg-red-500 rounded-full"
        ></span>
    </button>

    <!-- Dropdown des notifications -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute top-full z-50 origin-[60%_0] rounded-md bg-neutral-0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-neutral-904 ltr:-right-[110px] sm:ltr:right-0 sm:ltr:origin-top-right rtl:-left-[120px] sm:rtl:left-0 sm:rtl:origin-top-left w-[380px] max-w-[calc(100vw-20px)]"
    >
        <!-- En-tête -->
        <div class="flex items-center justify-between border-b p-3 dark:border-neutral-500 lg:px-4">
            <h5 class="h5">Notifications</h5>
            <div class="flex gap-2">
                @if($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-primary-300 hover:underline">
                        Tout marquer lu
                    </button>
                </form>
                @endif
                <a href="{{ route('notifications.index') }}" class="text-xs text-primary-300 hover:underline">
                    Voir tout
                </a>
            </div>
        </div>

        <!-- Liste des notifications -->
        <ul class="flex flex-col max-h-[400px] overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <i class="las la-bell-slash text-4xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-500">Aucune notification</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <li class="border-b last:border-0 dark:border-neutral-500">
                    <a
                        :href="notification.action_url || '#'"
                        class="flex gap-3 p-3 hover:bg-primary-300/5 transition-colors duration-200 cursor-pointer"
                        :class="!notification.is_read ? 'bg-blue-50/50 dark:bg-blue-900/20' : ''"
                    >
                        <!-- Icône -->
                        <div class="shrink-0">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center"
                                :class="{
                                    'bg-blue-100 text-blue-600': notification.type === 'order',
                                    'bg-green-100 text-green-600': notification.type === 'invoice',
                                    'bg-purple-100 text-purple-600': notification.type === 'payment',
                                    'bg-red-100 text-red-600': notification.type === 'warning',
                                    'bg-indigo-100 text-indigo-600': notification.type === 'project',
                                    'bg-orange-100 text-orange-600': notification.type === 'task',
                                    'bg-pink-100 text-pink-600': notification.type === 'customer',
                                    'bg-gray-100 text-gray-600': !notification.type
                                }"
                            >
                                <i class="text-xl" :class="{
                                    'las la-shopping-cart': notification.type === 'order',
                                    'las la-file-invoice': notification.type === 'invoice',
                                    'las la-credit-card': notification.type === 'payment',
                                    'las la-exclamation-triangle': notification.type === 'warning',
                                    'las la-project-diagram': notification.type === 'project',
                                    'las la-tasks': notification.type === 'task',
                                    'las la-user': notification.type === 'customer',
                                    'las la-bell': !notification.type
                                }"></i>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <p class="text-sm font-medium" x-text="notification.title"></p>
                                <span class="text-xs text-gray-400 whitespace-nowrap" x-text="notification.time_ago || 'À l\'instant'"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="notification.message"></p>

                            <!-- Actions si non lue -->
                            <div x-show="!notification.is_read" class="mt-2">
                                <button
                                    @click="markAsRead(notification.id, $event)"
                                    class="text-xs text-primary-300 hover:underline"
                                >
                                    Marquer comme lu
                                </button>
                            </div>
                        </div>

                        <!-- Indicateur non lu -->
                        <div x-show="!notification.is_read" class="shrink-0">
                            <span class="w-2 h-2 bg-blue-500 rounded-full block"></span>
                        </div>
                    </a>
                </li>
            </template>
        </ul>

        <!-- Pied de page -->
        <div class="border-t p-2 text-center dark:border-neutral-500">
            <a href="{{ route('notifications.index') }}" class="text-sm text-primary-300 hover:underline block py-1">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div>

<script>
// Rafraîchir les notifications toutes les 30 secondes
setInterval(() => {
    if (document.querySelector('[x-data]')?.__x?.$data) {
        document.querySelector('[x-data]').__x.$data.fetchNotifications();
    }
}, 30000);
</script>
