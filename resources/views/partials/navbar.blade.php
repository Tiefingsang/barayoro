<nav :class="[$store.app.sidebar && $store.app.menu == 'vertical' ?
    'w-full xl:ltr:ml-[280px] xl:w-[calc(100%-280px)] xl:rtl:mr-[280px]' : 'w-full', $store.app.sidebar && $store.app
    .menu == 'hovered' ? 'w-full xl:ltr:ml-[80px] xl:w-[calc(100%-80px)] xl:rtl:mr-[80px]' : 'w-full', $store.app
    .menu == 'horizontal' ? 'bg-neutral-20 dark:bg-neutral-903' : 'bg-neutral-0 dark:bg-neutral-904'
]"
    class="w-full fixed top-0 p-3 shadow-custom-4 duration-300 z-10">
    <div :class="$store.app.menu == 'horizontal' ? 'max-w-[1704px] w-full right-0 left-0 mx-auto' : ''"
        class="flex justify-between items-center">
        <div class="flex gap-4 xxl:gap-6 items-center">
            <!-- Logo -->
            <a x-show="$store.app.menu == 'horizontal'" href="{{ route('dashboard') }}"
                class="text-primary-300 flex gap-3 items-center max-xl:!hidden">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Votre SVG du logo -->
                </svg>
                <span class="h4 shrink-0 max-[380px]:hidden"><span
                        class="text-neutral-700 dark:text-neutral-0 h4">Barayoro</span></span>
            </a>

            <button :class="$store.app.menu == 'horizontal' ? 'xl:hidden' : ''" @click="$store.app.toggleSidebar()"><i
                    class="las la-bars text-2xl"></i></button>

            <!-- Barre de recherche -->
            <form
                :class="$store.app.menu == 'horizontal' ? 'bg-neutral-0 dark:bg-neutral-903' :
                    'bg-neutral-0 dark:bg-neutral-904'"
                class="max-w-[357px] max-md:hidden rounded-lg border focus-within:border-primary-300 border-neutral-30 dark:border-neutral-500 p-1 flex items-center">
                <input type="text" class="px-4 w-full bg-transparent text-sm" placeholder="Rechercher..." />
                <span class="size-8 shrink-0 rounded-full f-center">
                    <i class="las la-search text-xl"></i>
                </span>
            </form>
        </div>
        
        <div class="flex gap-3 xxl:gap-4 items-center">
            
            <!-- ========== BOUTON ABONNEMENT ========== -->
            @php
                $company = Auth::user()->company ?? null;
                $isExpired = $company && $company->isExpired();
                $isOnTrial = $company && $company->isOnTrial();
                $trialDaysRemaining = $company ? $company->getTrialDaysRemaining() : 0;
                $subscriptionDaysRemaining = $company ? $company->getSubscriptionDaysRemaining() : 0;
            @endphp

            @if($isExpired)
                <!-- Abonnement expiré - Rouge -->
                <a href="{{ route('subscription.plans') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    <i class="las la-exclamation-circle text-xl"></i>
                    <span class="hidden sm:inline">Abonnement expiré</span>
                    <span class="inline sm:hidden">Expiré</span>
                </a>
            @elseif($isOnTrial && $trialDaysRemaining <= 7)
                <!-- Essai bientôt expiré - Orange -->
                <a href="{{ route('subscription.plans') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                    <i class="las la-hourglass-half text-xl"></i>
                    <span class="hidden sm:inline">Essai: {{ $trialDaysRemaining }}j restants</span>
                    <span class="inline sm:hidden">{{ $trialDaysRemaining }}j</span>
                </a>
            @elseif($isOnTrial)
                <!-- En période d'essai - Vert clair -->
                <a href="{{ route('subscription.plans') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                    <i class="las la-gem text-xl"></i>
                    <span class="hidden sm:inline">Essai gratuit</span>
                    <span class="inline sm:hidden">Essai</span>
                </a>
            @elseif($company && $company->isSubscribed() && $subscriptionDaysRemaining <= 30)
                <!-- Abonnement bientôt expiré - Jaune/Orange -->
                <a href="{{ route('subscription.plans') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    <i class="las la-clock text-xl"></i>
                    <span class="hidden sm:inline">Renouvellement: {{ $subscriptionDaysRemaining }}j</span>
                    <span class="inline sm:hidden">{{ $subscriptionDaysRemaining }}j</span>
                </a>
            @elseif($company && $company->isSubscribed())
                <!-- Abonnement actif - Vert -->
                <a href="{{ route('subscription.plans') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    <i class="las la-check-circle text-xl"></i>
                    <span class="hidden sm:inline">Premium actif</span>
                    <span class="inline sm:hidden">Premium</span>
                </a>
            @else
                <!-- Pas d'abonnement - Bleu -->
                <a href="{{ route('subscription.plans') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="las la-credit-card text-xl"></i>
                    <span class="hidden sm:inline">S&#39;abonner</span>
                    <span class="inline sm:hidden">Abonnement</span>
                </a>
            @endif

            <!-- Bouton plein écran -->
            <button title="Basculer en plein écran"
                :class="$store.app.menu == 'horizontal' ? 'bg-neutral-0 dark:bg-neutral-903' :
                    'bg-neutral-20 dark:bg-neutral-903'"
                id="fullscreenButton"
                class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500">
                <i class="las la-expand text-xl full-screen-icon"></i>
            </button>
            
            <!-- Interrupteur thème clair/sombre -->
            <button title="Changer le thème"
                :class="$store.app.menu == 'horizontal' ? 'bg-neutral-0 dark:bg-neutral-903' :
                    'bg-neutral-20 dark:bg-neutral-903'"
                x-cloak x-show="$store.app.theme === 'light'" @click="$store.app.toggleTheme('dark')"
                class="flex size-9 items-center justify-center rounded-full border border-neutral-30 text-xl dark:border-neutral-500">
                <i class="las la-moon"></i>
            </button>
            <button title="Changer le thème" x-cloak x-show="$store.app.theme === 'dark'"
                @click="$store.app.toggleTheme('light')"
                class="flex size-9 items-center justify-center rounded-full border border-neutral-30 bg-neutral-20 text-xl dark:border-neutral-500 dark:bg-neutral-700">
                <i class="las la-sun"></i>
            </button>
            
            <!-- Notifications -->
            <x-notification-bell />

            <!-- Profil utilisateur -->
            <div x-data="dropdown" class="relative shrink-0">
                <div title="Profil utilisateur" @click="toggle" class="size-9 cursor-pointer">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('assets/images/users/user-s-4.png') }}" 
                         class="rounded-full" 
                         alt="photo de profil" />
                </div>
                <div @click.away="close" x-show="isOpen"
                    class="absolute top-full z-20 rounded-md bg-neutral-0 shadow-[0px_6px_30px_0px_rgba(0,0,0,0.08)] duration-300 dark:bg-neutral-904 ltr:right-0 ltr:origin-top-right rtl:left-0 rtl:origin-top-left">
                    <div
                        class="flex flex-col items-center border-b border-neutral-30 p-3 text-center dark:border-neutral-500 lg:p-4">
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('assets/images/users/user-s-4.png') }}" 
                             width="60" 
                             height="60" 
                             class="rounded-full"
                             alt="photo de profil" />
                        <h6 class="h6 mt-2">{{ Auth::user()->name ?? 'Utilisateur' }}</h6>
                        <span class="text-sm">{{ Auth::user()->email ?? 'email@exemple.com' }}</span>
                    </div>
                    <ul class="flex w-[250px] flex-col p-4">
                        <li>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                                <span><i class="las la-user mt-0.5 text-xl"></i></span>
                                Profil
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('chat') }}"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                                <span><i class="las la-envelope mt-0.5 text-xl"></i></span>
                                Messages
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('subscription.plans') }}"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                                <span><i class="las la-credit-card mt-0.5 text-xl"></i></span>
                                Abonnement
                                @if($isExpired)
                                    <span class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">Expiré</span>
                                @elseif($isOnTrial)
                                    <span class="ml-auto text-xs bg-green-500 text-white px-2 py-0.5 rounded-full">Essai</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('help.center') }}"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                                <span><i class="las la-life-ring mt-0.5 text-xl"></i></span>
                                Aide
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}#settings"
                                class="flex items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                                <span><i class="las la-cog mt-0.5 text-xl"></i></span>
                                Paramètres
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 duration-300 hover:bg-primary-300/10 hover:text-primary-300">
                                    <span><i class="las la-sign-out-alt mt-0.5 text-xl"></i></span>
                                    Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>