import Alpine from 'alpinejs';
import './bootstrap';
import './stores/sync';
import { syncService } from './services/sync';

window.Alpine = Alpine;
window.syncService = syncService;

document.addEventListener('DOMContentLoaded', () => {
    console.log('Sync service initialized');
    if (navigator.onLine && window.syncService) {
        setTimeout(() => window.syncService.sync(), 2000);
    }
});



// ==================== PWA PRO ====================

class PWAManager {
    constructor() {
        this.swRegistration = null;
        this.deferredPrompt = null;
        this.isOnline = navigator.onLine;
        this.init();
    }
    
    async init() {
        await this.registerServiceWorker();
        this.setupEventListeners();
        this.checkForUpdates();
        this.setupBackgroundSync();
        this.setupPushNotifications();
    }
    
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                this.swRegistration = await navigator.serviceWorker.register('/sw.js');
                console.log('[PWA] Service Worker enregistré:', this.swRegistration);
                
                // Vérifier les mises à jour
                this.swRegistration.addEventListener('updatefound', () => {
                    const newWorker = this.swRegistration.installing;
                    console.log('[PWA] Nouvelle version du Service Worker trouvée');
                    
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateNotification();
                        }
                    });
                });
                
            } catch (error) {
                console.error('[PWA] Erreur d\'enregistrement:', error);
            }
        }
    }
    
    setupEventListeners() {
        // Détection online/offline
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.showToast('🟢 Connexion rétablie', 'success');
            this.syncData();
        });
        
        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.showToast('🔴 Mode hors ligne activé', 'warning');
        });
        
        // Installation PWA
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });
        
        // Messages du Service Worker
        navigator.serviceWorker.addEventListener('message', (event) => {
            const data = event.data;
            
            switch (data.action) {
                case 'backgroundSync':
                    this.syncData();
                    break;
                case 'syncRequired':
                    this.syncData();
                    break;
                case 'cacheSize':
                    console.log('[PWA] Taille du cache:', data.size);
                    break;
            }
        });
    }
    
    async setupBackgroundSync() {
        if ('sync' in navigator.serviceWorker) {
            try {
                await navigator.serviceWorker.ready;
                await navigator.serviceWorker.sync.register('sync-data');
                console.log('[PWA] Background sync enregistré');
            } catch (error) {
                console.error('[PWA] Erreur background sync:', error);
            }
        }
    }
    
    async setupPushNotifications() {
        if ('Notification' in window && 'PushManager' in window) {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                console.log('[PWA] Notifications push autorisées');
                await this.subscribeToPush();
            }
        }
    }
    
    async subscribeToPush() {
        try {
            const subscription = await this.swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array('VOTRE_CLE_PUBLIQUE')
            });
            
            // Envoyer la subscription au serveur
            await fetch('/api/push/subscribe', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(subscription)
            });
            
        } catch (error) {
            console.error('[PWA] Erreur subscription push:', error);
        }
    }
    
    async syncData() {
        if (!this.isOnline) return;
        
        console.log('[PWA] Synchronisation des données...');
        this.showToast('🔄 Synchronisation en cours...', 'info');
        
        try {
            if (window.syncService) {
                await window.syncService.sync();
                this.showToast('✅ Données synchronisées', 'success');
            }
        } catch (error) {
            console.error('[PWA] Erreur synchronisation:', error);
            this.showToast('❌ Erreur de synchronisation', 'error');
        }
    }
    
    showInstallButton() {
        const installBtn = document.createElement('div');
        installBtn.innerHTML = `
            <div class="fixed bottom-20 right-4 z-50">
                <button id="pwa-install-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Installer l'application
                </button>
            </div>
        `;
        document.body.appendChild(installBtn);
        
        document.getElementById('pwa-install-btn').addEventListener('click', async () => {
            if (this.deferredPrompt) {
                this.deferredPrompt.prompt();
                const { outcome } = await this.deferredPrompt.userChoice;
                console.log(`[PWA] Installation: ${outcome}`);
                this.deferredPrompt = null;
                installBtn.remove();
            }
        });
    }
    
    showUpdateNotification() {
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div class="fixed bottom-20 right-4 z-50 bg-blue-600 text-white px-4 py-3 rounded-lg shadow-lg">
                <div class="flex items-center gap-3">
                    <span>🔄 Nouvelle version disponible</span>
                    <button id="update-btn" class="bg-white text-blue-600 px-3 py-1 rounded">Mettre à jour</button>
                </div>
            </div>
        `;
        document.body.appendChild(notification);
        
        document.getElementById('update-btn').addEventListener('click', () => {
            window.location.reload();
        });
        
        setTimeout(() => notification.remove(), 10000);
    }
    
    showToast(message, type = 'info') {
        const colors = {
            success: 'bg-green-600',
            error: 'bg-red-600',
            warning: 'bg-yellow-600',
            info: 'bg-blue-600'
        };
        
        const toast = document.createElement('div');
        toast.innerHTML = `
            <div class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50 ${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg animate-fade-in-up">
                ${message}
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 3000);
    }
    
    checkForUpdates() {
        setInterval(() => {
            if (this.swRegistration) {
                this.swRegistration.update();
            }
        }, 60 * 60 * 1000); // Vérifier toutes les heures
    }
    
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
}

// Initialiser le PWA Manager
document.addEventListener('DOMContentLoaded', () => {
    window.pwaManager = new PWAManager();
});


Alpine.start();
