const CACHE_VERSION = 'v3';
const CACHE_NAME = `barayoro-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

// Fichiers à mettre en cache lors de l'installation
const STATIC_CACHE_URLS = [
    '/offline.html',
    '/manifest.json',
    '/css/app.css',
    '/js/app.js'
];

// Stratégies de cache
const STRATEGIES = {
    CACHE_FIRST: 'cache-first',
    NETWORK_FIRST: 'network-first',
    STALE_WHILE_REVALIDATE: 'stale-while-revalidate',
    CACHE_ONLY: 'cache-only',
    NETWORK_ONLY: 'network-only'
};

// Configuration des routes - MODIFIÉ POUR TOUJOURS ALLER SUR LE RÉSEAU POUR LES PAGES
const ROUTES = [
    {
        // Pour les assets statiques - cache first
        pattern: /\.(css|js|json|svg|png|jpg|jpeg|gif|ico)$/,
        strategy: STRATEGIES.CACHE_FIRST,
        maxEntries: 100
    },
    {
        // Pour les pages HTML - TOUJOURS réseau d'abord
        pattern: /\/$/,
        strategy: STRATEGIES.NETWORK_ONLY
    },
    {
        // Pour les routes spécifiques - NETWORK FIRST (pas de cache)
        pattern: /^\/(dashboard|profile|chat|clients|products|invoices|projects|tasks|calendar|kanban|mail|help-center|blog|jobs|tours|reviews|referrals|about|faq|pricing)/,
        strategy: STRATEGIES.NETWORK_ONLY  // Changé de STALE_WHILE_REVALIDATE à NETWORK_ONLY
    },
    {
        // Pour l'API - network first avec timeout
        pattern: /^\/api\/(?!auth).*/,
        strategy: STRATEGIES.NETWORK_FIRST,
        timeout: 3000,
        maxAge: 24 * 60 * 60,
        maxEntries: 50
    },
    {
        // Pages d'auth - toujours réseau
        pattern: /^\/(login|register|forgot-password|reset-password)/,
        strategy: STRATEGIES.NETWORK_ONLY
    }
];

// Installation
self.addEventListener('install', event => {
    console.log('[SW] Installation...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[SW] Mise en cache des ressources statiques');
                return cache.addAll(STATIC_CACHE_URLS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activation - nettoyage des anciens caches
self.addEventListener('activate', event => {
    console.log('[SW] Activation...');
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames
                        .filter(name => name !== CACHE_NAME && name.startsWith('barayoro-'))
                        .map(name => {
                            console.log(`[SW] Suppression de l'ancien cache: ${name}`);
                            return caches.delete(name);
                        })
                );
            })
            .then(() => self.clients.claim())
    );
});

// Récupérer la stratégie pour une requête
function getStrategyForRequest(request) {
    const url = new URL(request.url);
    const route = ROUTES.find(r => r.pattern.test(url.pathname));
    return route || { strategy: STRATEGIES.NETWORK_FIRST, timeout: 5000 };
}

// Stratégie: Cache First
async function cacheFirst(request, options = {}) {
    const cache = await caches.open(CACHE_NAME);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
        console.log(`[SW] Cache hit: ${request.url}`);
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        if (networkResponse && networkResponse.status === 200) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error(`[SW] Network error: ${request.url}`, error);
        return caches.match(OFFLINE_URL);
    }
}

// Stratégie: Network First avec timeout
async function networkFirst(request, options = {}) {
    const timeout = options.timeout || 5000;
    const cache = await caches.open(CACHE_NAME);
    
    try {
        const timeoutPromise = new Promise((_, reject) => {
            setTimeout(() => reject(new Error('Timeout')), timeout);
        });
        
        const fetchPromise = fetch(request);
        const response = await Promise.race([fetchPromise, timeoutPromise]);
        
        if (response && response.status === 200) {
            // Ne pas mettre en cache les pages HTML
            const url = new URL(request.url);
            const isHtmlPage = !request.url.match(/\.(css|js|json|svg|png|jpg|jpeg|gif|ico)$/);
            
            if (!isHtmlPage) {
                cache.put(request, response.clone());
            }
            return response;
        }
        
        throw new Error('Network response not OK');
    } catch (error) {
        console.log(`[SW] Network failed, trying cache: ${request.url}`);
        const cachedResponse = await cache.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        if (request.url.includes('/api/')) {
            return new Response(JSON.stringify({
                error: 'offline',
                message: 'Vous êtes hors ligne. Les modifications seront synchronisées automatiquement.'
            }), {
                status: 503,
                headers: { 'Content-Type': 'application/json' }
            });
        }
        
        // Rediriger vers la page offline seulement si ce n'est pas une requête API
        if (!request.url.includes('/api/')) {
            return caches.match(OFFLINE_URL);
        }
        
        return new Response('Offline', { status: 503 });
    }
}

// Stratégie: Stale While Revalidate
async function staleWhileRevalidate(request, options = {}) {
    const cache = await caches.open(CACHE_NAME);
    const cachedResponse = await cache.match(request);
    
    const fetchPromise = fetch(request).then(networkResponse => {
        if (networkResponse && networkResponse.status === 200) {
            // Ne pas mettre en cache les pages HTML
            const isHtmlPage = !request.url.match(/\.(css|js|json|svg|png|jpg|jpeg|gif|ico)$/);
            if (!isHtmlPage) {
                cache.put(request, networkResponse.clone());
            }
        }
        return networkResponse;
    }).catch(error => {
        console.log(`[SW] Background sync failed: ${request.url}`, error);
    });
    
    if (cachedResponse) {
        event.waitUntil(fetchPromise);
        return cachedResponse;
    }
    
    return fetchPromise;
}

// Stratégie: Network Only (PAS DE CACHE)
async function networkOnly(request, options = {}) {
    try {
        const response = await fetch(request);
        return response;
    } catch (error) {
        console.log(`[SW] Network only failed: ${request.url}`);
        
        // Pour les pages, rediriger vers offline
        if (!request.url.includes('/api/')) {
            return caches.match(OFFLINE_URL);
        }
        
        return new Response(JSON.stringify({
            error: 'offline',
            message: 'Vous êtes hors ligne'
        }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Interception des requêtes
self.addEventListener('fetch', event => {
    const request = event.request;
    
    // Ignorer les requêtes non-GET
    if (request.method !== 'GET') {
        return;
    }
    
    // Ignorer les analytics
    if (request.url.includes('google-analytics') || request.url.includes('facebook')) {
        return;
    }
    
    const { strategy, ...options } = getStrategyForRequest(request);
    
    let handler;
    switch (strategy) {
        case STRATEGIES.CACHE_FIRST:
            handler = cacheFirst(request, options);
            break;
        case STRATEGIES.NETWORK_FIRST:
            handler = networkFirst(request, options);
            break;
        case STRATEGIES.STALE_WHILE_REVALIDATE:
            handler = staleWhileRevalidate(request, options);
            break;
        case STRATEGIES.NETWORK_ONLY:
            handler = networkOnly(request, options);
            break;
        default:
            handler = networkFirst(request, options);
    }
    
    event.respondWith(handler);
});

// Gestion des messages
self.addEventListener('message', event => {
    const data = event.data;
    
    switch (data.action) {
        case 'skipWaiting':
            self.skipWaiting();
            break;
        case 'clearCache':
            caches.delete(CACHE_NAME);
            break;
    }
});