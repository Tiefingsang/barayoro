import { syncService } from '../services/sync';

document.addEventListener('alpine:init', () => {
    Alpine.store('sync', {
        status: 'idle',
        pendingCount: 0,
        lastSync: null,
        error: null,

        init() {
            syncService.addListener((event, data) => {
                switch(event) {
                    case 'online':
                        this.status = 'online';
                        break;
                    case 'offline':
                        this.status = 'offline';
                        break;
                    case 'sync_started':
                        this.status = 'syncing';
                        break;
                    case 'sync_completed':
                        this.status = navigator.onLine ? 'online' : 'offline';
                        this.lastSync = new Date();
                        this.updatePendingCount();
                        break;
                    case 'sync_failed':
                        this.status = 'error';
                        this.error = data.error;
                        break;
                    case 'pending_changed':
                        this.updatePendingCount();
                        break;
                }
            });

            this.updatePendingCount();
            setInterval(() => this.updatePendingCount(), 5000);
        },

        async updatePendingCount() {
            this.pendingCount = await syncService.getPendingCount();
        },

        async sync() {
            if (this.status === 'syncing') return;
            await syncService.sync();
        }
    });
});
