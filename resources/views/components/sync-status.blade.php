<div x-data="{
    status: 'online',
    pendingCount: 0
}"
 x-init="
    // Écouter les événements de synchronisation
    if (window.syncService) {
        window.syncService.addListener((event, data) => {
            if (event === 'sync_started') status = 'syncing';
            else if (event === 'sync_completed') status = 'online';
            else if (event === 'sync_failed') status = 'error';
            else if (event === 'offline') status = 'offline';
            else if (event === 'online') status = 'online';
            else if (event === 'pending_changed') pendingCount = data.count;
        });
    }

    // Mettre à jour le compteur périodiquement
    setInterval(async () => {
        if (window.syncService) {
            pendingCount = await window.syncService.getPendingCount();
            status = navigator.onLine ? (pendingCount > 0 ? 'online' : 'online') : 'offline';
        }
    }, 3000);
"
 class="fixed bottom-4 right-4 z-50">

    <!-- Hors ligne -->
    <div x-show="status === 'offline'" x-cloak
         class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 dark:text-yellow-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <i class="las la-wifi-slash text-lg"></i>
        <span class="text-sm">Hors ligne</span>
    </div>

    <!-- Synchronisation en cours -->
    <div x-show="status === 'syncing'" x-cloak
         class="bg-blue-100 dark:bg-blue-900/30 border border-blue-400 text-blue-700 dark:text-blue-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm">Synchronisation...</span>
    </div>

    <!-- En ligne avec opérations en attente -->
    <div x-show="status === 'online' && pendingCount > 0" x-cloak
         class="bg-orange-100 dark:bg-orange-900/30 border border-orange-400 text-orange-700 dark:text-orange-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 cursor-pointer hover:bg-orange-200 transition"
         @click="if(window.syncService) window.syncService.sync()">
        <i class="las la-sync-alt text-lg animate-pulse"></i>
        <span class="text-sm" x-text="pendingCount + ' modification(s) en attente'"></span>
    </div>

    <!-- En ligne - tout est synchronisé -->
    <div x-show="status === 'online' && pendingCount === 0" x-cloak
         class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 dark:text-green-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <i class="las la-check-circle text-lg"></i>
        <span class="text-sm">Synchronisé</span>
    </div>

    <!-- Erreur -->
    <div x-show="status === 'error'" x-cloak
         class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 dark:text-red-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 cursor-pointer"
         @click="if(window.syncService) window.syncService.sync()">
        <i class="las la-exclamation-circle text-lg"></i>
        <span class="text-sm">Erreur - Réessayer</span>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
