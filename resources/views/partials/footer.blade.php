<footer
  :class="[$store.app.sidebar && $store.app.menu=='vertical'?'w-full xl:ltr:ml-[280px] xl:rtl:mr-[280px] xl:w-[calc(100%-280px)]':'w-full',$store.app.sidebar && $store.app.menu=='hovered'?'w-full xl:ltr:ml-[80px] xl:w-[calc(100%-80px)] xl:rtl:mr-[80px]':'w-full']"
  class="footer bg-neutral-0 dark:bg-neutral-904 text-neutral-700 dark:text-neutral-20"
>
  <div :class="$store.app.menu=='horizontal' ? 'max-w-[1704px] mx-auto':''" class="flex flex-col items-center justify-center gap-3 px-4 py-5 lg:flex-row lg:justify-between xxl:px-8 xxl:py-6">

    <!-- Copyright -->
    <p class="text-sm max-md:w-full max-md:text-center">
      Copyright © <span id="current-year"></span>
      <a class="text-primary-300 font-medium" href="https://masadigitale.com"> Masadigitale </a>
      . Tous droits réservés
    </p>

    <!-- Statut d'abonnement -->
    <div class="text-sm">
      @auth
        @php
          $company = auth()->user()->company;
        @endphp

        @if($company && $company->isOnTrial())
          <div class="flex items-center gap-2 text-yellow-600 dark:text-yellow-400">
            <i class="las la-gem text-lg"></i>
            <span>Mode gratuit - Essai</span>
            <span class="font-semibold">{{ $company->getTrialDaysRemaining() }} jour(s) restant(s)</span>
          </div>
        @elseif($company && $company->isSubscribed())
          <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
            <i class="las la-check-circle text-lg"></i>
            <span>Abonnement actif</span>
            <span class="font-semibold">{{ $company->getSubscriptionDaysRemaining() }} jour(s) restant(s)</span>
          </div>
        @elseif($company && $company->isExpired())
          <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
            <i class="las la-exclamation-circle text-lg"></i>
            <span>Abonnement expiré</span>
            <a href="{{ route('subscription.plans') }}" class="ml-2 text-primary-300 hover:underline">Renouveler</a>
          </div>
        @else
          <div class="flex items-center gap-2 text-gray-500">
            <i class="las la-clock text-lg"></i>
            <span>Abonnement en attente</span>
          </div>
        @endif
      @endauth
    </div>

    <!-- Liens -->
    <ul class="flex gap-3 text-sm max-lg:w-full max-lg:justify-center lg:gap-4">
      <li>
        <a href="{{ route('help.center') }}" class="footer-link">Centre d'aide</a>
      </li>
      <li>
        <a href="{{ route('privacy') }}" class="footer-link">Politique de confidentialité</a>
      </li>
    </ul>
  </div>
</footer>
