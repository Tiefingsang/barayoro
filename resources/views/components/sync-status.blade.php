{{-- <div x-data="{
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
 --}}




 <div x-data="{
    status: 'online',
    pendingCount: 0,
    showStatus: false,
    timeoutId: null,
    
    // Afficher le message temporairement
    showTemporaryMessage(duration = 5000) {
        this.showStatus = true;
        
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }
        
        this.timeoutId = setTimeout(() => {
            if (this.pendingCount === 0 && this.status !== 'offline' && this.status !== 'syncing') {
                this.showStatus = false;
            }
        }, duration);
    },
    
    // Cacher le message
    hideStatus() {
        this.showStatus = false;
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }
    },
    
    // Mettre à jour et afficher le message
    updateAndShow(messageStatus, autoHide = true) {
        this.status = messageStatus;
        
        if (messageStatus === 'online' && this.pendingCount === 0) {
            this.showTemporaryMessage(autoHide ? 3000 : 5000);
        } else if (messageStatus === 'offline') {
            this.showStatus = true;
        } else if (messageStatus === 'syncing') {
            this.showStatus = true;
        } else if (messageStatus === 'error') {
            this.showTemporaryMessage(5000);
        } else if (messageStatus === 'online' && this.pendingCount > 0) {
            this.showStatus = true;
        } else {
            this.showStatus = true;
        }
    }
}"
 x-init="
    // Fonction pour la connexion rétablie
    const handleOnline = () => {
        status = 'online';
        updateAndShow('online', true);
        if (window.syncService) {
            setTimeout(async () => {
                pendingCount = await window.syncService.getPendingCount() || 0;
                if (pendingCount > 0) {
                    setTimeout(() => {
                        if (window.syncService) window.syncService.sync();
                    }, 500);
                }
            }, 100);
        }
    };
    
    // Fonction pour la déconnexion
    const handleOffline = () => {
        status = 'offline';
        updateAndShow('offline', false);
    };
    
    // Écouter les événements réseau
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
    
    // Vérifier l'état initial
    if (!navigator.onLine) {
        status = 'offline';
        updateAndShow('offline', false);
    } else {
        status = 'online';
        setTimeout(() => {
            updateAndShow('online', true);
        }, 500);
    }

    // Écouter les événements de synchronisation
    if (window.syncService) {
        window.syncService.addListener(async (event, data) => {
            if (event === 'sync_started') {
                updateAndShow('syncing', false);
            } 
            else if (event === 'sync_completed') {
                pendingCount = 0;
                updateAndShow('online', true);
                setTimeout(() => {
                    if (pendingCount === 0 && status === 'online') {
                        hideStatus();
                    }
                }, 3000);
            } 
            else if (event === 'sync_failed') {
                updateAndShow('error', true);
            } 
            else if (event === 'offline') {
                status = 'offline';
                updateAndShow('offline', false);
            } 
            else if (event === 'online') {
                status = 'online';
                updateAndShow('online', true);
            } 
            else if (event === 'pending_changed') {
                pendingCount = data.count;
                if (pendingCount > 0) {
                    updateAndShow('online', false);
                } else {
                    updateAndShow('online', true);
                    setTimeout(() => {
                        if (pendingCount === 0 && status === 'online') {
                            hideStatus();
                        }
                    }, 3000);
                }
            }
        });
    }

    // Mettre à jour le compteur périodiquement
    const updatePendingCount = async () => {
        if (window.syncService) {
            try {
                const count = await window.syncService.getPendingCount() || 0;
                if (count !== pendingCount) {
                    pendingCount = count;
                    if (pendingCount > 0) {
                        updateAndShow('online', false);
                    } else if (status === 'online') {
                        updateAndShow('online', true);
                        setTimeout(() => {
                            if (pendingCount === 0 && status === 'online') {
                                hideStatus();
                            }
                        }, 3000);
                    }
                }
            } catch (e) {
                console.error('Erreur lors de la récupération du compteur:', e);
            }
        }
    };
    
    // Vérifier toutes les 10 secondes
    const intervalId = setInterval(updatePendingCount, 10000);
    
    // Nettoyage au démontage
    const cleanup = () => {
        window.removeEventListener('online', handleOnline);
        window.removeEventListener('offline', handleOffline);
        if (timeoutId) clearTimeout(timeoutId);
        clearInterval(intervalId);
    };
    
    window.addEventListener('beforeunload', cleanup);
    
    // Nettoyer si l'élément est retiré du DOM
    const observer = new MutationObserver(() => {
        if (!document.body.contains(this.$el)) {
            cleanup();
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
"
 class="fixed bottom-4 right-4 z-50">

    <!-- Hors ligne - reste affiché tant qu'on est hors ligne -->
    <div x-show="showStatus && status === 'offline'" x-cloak
         x-transition.duration.300ms
         class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 dark:text-yellow-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <i class="las la-wifi-slash text-lg"></i>
        <span class="text-sm">Hors ligne - Connexion perdue</span>
    </div>

    <!-- Synchronisation en cours -->
    <div x-show="showStatus && status === 'syncing'" x-cloak
         x-transition.duration.300ms
         class="bg-blue-100 dark:bg-blue-900/30 border border-blue-400 text-blue-700 dark:text-blue-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm">Synchronisation en cours...</span>
    </div>

    <!-- En ligne avec opérations en attente - reste affiché -->
    <div x-show="showStatus && status === 'online' && pendingCount > 0" x-cloak
         x-transition.duration.300ms
         class="bg-orange-100 dark:bg-orange-900/30 border border-orange-400 text-orange-700 dark:text-orange-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 cursor-pointer hover:bg-orange-200 transition"
         @click="if(window.syncService) window.syncService.sync()">
        <i class="las la-sync-alt text-lg animate-pulse"></i>
        <span class="text-sm" x-text="pendingCount + ' modification(s) en attente de synchronisation'"></span>
    </div>

    <!-- En ligne - tout est synchronisé (disparaît automatiquement après 3 secondes) -->
    <div x-show="showStatus && status === 'online' && pendingCount === 0" x-cloak
         x-transition.duration.300ms
         class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 dark:text-green-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <i class="las la-check-circle text-lg"></i>
        <span class="text-sm">✓ Synchronisé</span>
    </div>

    <!-- Erreur (disparaît automatiquement après 5 secondes) -->
    <div x-show="showStatus && status === 'error'" x-cloak
         x-transition.duration.300ms
         class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 dark:text-red-400 px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 cursor-pointer hover:bg-red-200 transition"
         @click="if(window.syncService) window.syncService.sync()">
        <i class="las la-exclamation-circle text-lg"></i>
        <span class="text-sm">Échec de synchronisation - Cliquez pour réessayer</span>
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
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    /* Transition d'entrée/sortie */
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(10px);
    }
</style>