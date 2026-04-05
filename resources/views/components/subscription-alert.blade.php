@auth
@php
    $company = auth()->user()->company;
@endphp

@if($company && ($company->isOnTrial() || $company->isSubscribed()))
    @php
        $daysRemaining = $company->isOnTrial() ? $company->getTrialDaysRemaining() : $company->getSubscriptionDaysRemaining();
        $isExpiringSoon = $daysRemaining <= 30; // Moins de 30 jours
    @endphp

    @if($isExpiringSoon)
    <div x-data="subscriptionAlert()"
         x-init="init({{ $daysRemaining }}, '{{ $company->isOnTrial() ? 'trial' : 'subscription' }}')"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <!-- En-tête -->
            <div class="relative bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                <div class="absolute top-0 right-0 p-3">
                    <button @click="close()" class="text-white hover:text-gray-200 transition">
                        <i class="las la-times text-2xl"></i>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="las la-exclamation-triangle text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">⚠️ Attention</h3>
                        <p class="text-white/80 text-sm">Votre abonnement expire bientôt</p>
                    </div>
                </div>
            </div>

            <!-- Corps -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-orange-100 dark:bg-orange-900/30 mb-4">
                        <i class="las la-hourglass-half text-4xl text-orange-500"></i>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-2">
                        <span class="font-bold text-2xl text-orange-600">{{ $daysRemaining }}</span>
                        <span class="text-lg"> jour(s) restant(s)</span>
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        @if($company->isOnTrial())
                            Votre période d'essai gratuit se termine dans <strong>{{ $daysRemaining }} jours</strong>.
                            Après cette date, vous ne pourrez plus accéder à vos données.
                        @else
                            Votre abonnement annuel expire dans <strong>{{ $daysRemaining }} jours</strong>.
                            Renouvelez dès maintenant pour continuer à utiliser Barayoro sans interruption.
                        @endif
                    </p>
                </div>

                <!-- Compteur -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Temps restant</span>
                        <div class="flex gap-2 text-center">
                            <div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg px-3 py-2 shadow-sm">
                                    <span x-text="days" class="text-2xl font-bold text-orange-600"></span>
                                    <span class="text-xs text-gray-500 block">Jours</span>
                                </div>
                            </div>
                            <div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg px-3 py-2 shadow-sm">
                                    <span x-text="hours" class="text-2xl font-bold text-orange-600"></span>
                                    <span class="text-xs text-gray-500 block">Heures</span>
                                </div>
                            </div>
                            <div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg px-3 py-2 shadow-sm">
                                    <span x-text="minutes" class="text-2xl font-bold text-orange-600"></span>
                                    <span class="text-xs text-gray-500 block">Minutes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('subscription.plans') }}"
                       class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                        <i class="las la-credit-card mr-2"></i>
                        Renouveler mon abonnement
                    </a>
                    <button @click="remindLater()"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-sm py-2 transition">
                        Me le rappeler plus tard
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif
@endauth

@push('scripts')
<script>
function subscriptionAlert() {
    return {
        show: false,
        days: 0,
        hours: 0,
        minutes: 0,
        daysRemaining: 0,
        type: 'subscription',
        timer: null,

        init(daysRemaining, type) {
            this.daysRemaining = daysRemaining;
            this.type = type;
            this.startCountdown();
            this.checkAndShow();

            // Vérifier toutes les heures
            setInterval(() => this.checkAndShow(), 3600000);
        },

        startCountdown() {
            if (this.timer) clearInterval(this.timer);

            this.timer = setInterval(() => {
                if (this.daysRemaining <= 0) {
                    clearInterval(this.timer);
                    return;
                }

                const now = new Date();
                const endDate = new Date();
                endDate.setDate(endDate.getDate() + this.daysRemaining);

                const diff = endDate - now;

                this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((diff % (86400000)) / (1000 * 60 * 60));
                this.minutes = Math.floor((diff % (3600000)) / (1000 * 60));

                this.daysRemaining = this.days;
            }, 60000);
        },

        checkAndShow() {
            // Vérifier si l'alerte a déjà été vue aujourd'hui
            const lastShown = localStorage.getItem('subscription_alert_last_shown');
            const today = new Date().toDateString();

            if (lastShown !== today) {
                // Afficher plus fréquemment quand il reste moins de 7 jours
                if (this.daysRemaining <= 7) {
                    this.show = true;
                }
                // Afficher tous les 3 jours quand il reste moins de 30 jours
                else if (this.daysRemaining <= 30) {
                    const lastShownDate = lastShown ? new Date(lastShown) : null;
                    const daysSinceLastShown = lastShownDate ? Math.floor((new Date() - lastShownDate) / (1000 * 60 * 60 * 24)) : 3;

                    if (!lastShown || daysSinceLastShown >= 3) {
                        this.show = true;
                    }
                }
            }
        },

        close() {
            this.show = false;
            localStorage.setItem('subscription_alert_last_shown', new Date().toDateString());
        },

        remindLater() {
            this.close();
            // Stocker le rappel pour 3 jours plus tard
            const remindDate = new Date();
            remindDate.setDate(remindDate.getDate() + 3);
            localStorage.setItem('subscription_alert_remind_later', remindDate.toISOString());
        }
    }
}
</script>
@endpush
