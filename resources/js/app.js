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


Alpine.start();
