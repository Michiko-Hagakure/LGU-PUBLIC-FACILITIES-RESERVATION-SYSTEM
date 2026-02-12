/**
 * LGU1 PFRS - Service Worker
 * Provides offline caching for static assets, UI shell, and API responses.
 * Strategy: Cache-first for static assets, Network-first for API/pages.
 */

const CACHE_VERSION = 'lgu1-pfrs-v3';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;
const API_CACHE = `${CACHE_VERSION}-api`;
const CDN_CACHE = `${CACHE_VERSION}-cdn`;

// Static assets to pre-cache on install
const PRECACHE_ASSETS = [
    '/',
    '/offline',
    '/assets/images/logo.png',
    '/assets/images/logo-tiny.png',
    '/manifest.json',
];

// Critical CDN resources to cache on first fetch
const CDN_DOMAINS = [
    'cdn.tailwindcss.com',
    'cdn.jsdelivr.net',
    'unpkg.com',
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    'fonts.bunny.net',
];

// Critical CDN URLs to precache during install
const CDN_PRECACHE = [
    'https://cdn.tailwindcss.com',
    'https://unpkg.com/lucide@latest',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
    'https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap',
    'https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700',
];

// URL patterns that should use network-first strategy
const NETWORK_FIRST_PATTERNS = [
    /\/api\//,
    /\/citizen\//,
    /\/admin\//,
    /\/staff\//,
    /\/treasurer\//,
    /\/cbd\//,
    /\/login/,
    /\/register/,
    /\/logout/,
    /\/ping-session/,
];

// URL patterns to never cache
const NEVER_CACHE_PATTERNS = [
    /\/logout/,
    /\/ping-session/,
    /\/sanctum\//,
    /livewire/,
    /\_debugbar/,
];

// URL patterns for cacheable API GET responses (read-only data)
const CACHEABLE_API_PATTERNS = [
    /\/api\/facilities/,
    /\/api\/bookings/,
    /\/api\/announcements/,
    /\/citizen\/facilities/,
    /\/citizen\/my-bookings/,
    /\/citizen\/dashboard/,
    /\/admin\/dashboard/,
    /\/staff\/dashboard/,
];

/**
 * INSTALL: Pre-cache essential static assets
 */
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker...');
    event.waitUntil(
        Promise.all([
            caches.open(STATIC_CACHE)
                .then((cache) => {
                    console.log('[SW] Pre-caching static assets');
                    return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                        console.warn('[SW] Some precache assets failed:', err);
                        return Promise.resolve();
                    });
                }),
            caches.open(CDN_CACHE)
                .then((cache) => {
                    console.log('[SW] Pre-caching CDN resources');
                    // Cache each CDN resource individually so one failure doesn't block others
                    // Also cache the final redirected URL so both original and redirected URLs are cached
                    return Promise.all(
                        CDN_PRECACHE.map((url) =>
                            fetch(url, { mode: 'cors', redirect: 'follow' })
                                .then((response) => {
                                    if (response.ok) {
                                        var promises = [cache.put(url, response.clone())];
                                        // Also cache the final URL if it differs from the request (redirect)
                                        if (response.url && response.url !== url) {
                                            promises.push(cache.put(response.url, response.clone()));
                                        }
                                        return Promise.all(promises);
                                    }
                                })
                                .catch((err) => {
                                    console.warn('[SW] CDN precache failed for:', url, err.message);
                                })
                        )
                    );
                }),
        ]).then(() => self.skipWaiting())
    );
});

/**
 * ACTIVATE: Clean up old caches
 */
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker...');
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((name) => name.startsWith('lgu1-pfrs-') && name !== STATIC_CACHE && name !== DYNAMIC_CACHE && name !== API_CACHE && name !== CDN_CACHE)
                        .map((name) => {
                            console.log('[SW] Deleting old cache:', name);
                            return caches.delete(name);
                        })
                );
            })
            .then(() => self.clients.claim())
    );
});

/**
 * FETCH: Intercept network requests with appropriate caching strategies
 */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests (POST, PUT, DELETE go through the offline queue)
    if (request.method !== 'GET') {
        return;
    }

    // Handle CDN/cross-origin requests (fonts, Tailwind, JS libraries)
    if (url.origin !== self.location.origin) {
        if (CDN_DOMAINS.some((domain) => url.hostname === domain || url.hostname.endsWith('.' + domain))) {
            event.respondWith(cdnCacheFirst(request));
        }
        return;
    }

    // Never cache certain patterns
    if (NEVER_CACHE_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
        return;
    }

    // Static assets: Cache-first strategy
    if (isStaticAsset(url.pathname)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
        return;
    }

    // Cacheable API/page responses: Network-first with cache fallback
    if (CACHEABLE_API_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
        event.respondWith(networkFirst(request, API_CACHE));
        return;
    }

    // Network-first patterns (authenticated pages)
    if (NETWORK_FIRST_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
        return;
    }

    // Default: Network-first for everything else
    event.respondWith(networkFirst(request, DYNAMIC_CACHE));
});

/**
 * CDN cache-first strategy for cross-origin resources (fonts, CSS, JS libraries)
 */
async function cdnCacheFirst(request) {
    try {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        const response = await fetch(request, { mode: 'cors', redirect: 'follow' });
        if (response.ok) {
            const cache = await caches.open(CDN_CACHE);
            cache.put(request, response.clone());
            // Also cache the final redirected URL
            if (response.url && response.url !== request.url) {
                cache.put(response.url, response.clone());
            }
        }
        return response;
    } catch (error) {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        return new Response('', { status: 503, statusText: 'CDN Unavailable' });
    }
}

/**
 * Cache-first strategy: Try cache, fallback to network
 */
async function cacheFirst(request, cacheName) {
    try {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        return caches.match('/offline');
    }
}

/**
 * Network-first strategy: Try network, fallback to cache
 */
async function networkFirst(request, cacheName) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        // If it's a navigation request, show offline page
        if (request.mode === 'navigate') {
            return caches.match('/offline');
        }
        return new Response('Offline', { status: 503, statusText: 'Service Unavailable' });
    }
}

/**
 * Check if a URL path points to a static asset
 */
function isStaticAsset(pathname) {
    return /\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|webp)(\?.*)?$/.test(pathname) ||
           pathname.startsWith('/build/') ||
           pathname.startsWith('/assets/');
}

/**
 * Listen for messages from the main thread
 */
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        const urls = event.data.urls || [];
        event.waitUntil(
            caches.open(DYNAMIC_CACHE).then((cache) => {
                return Promise.all(
                    urls.map((url) => cache.add(url).catch(() => {}))
                );
            })
        );
    }

    if (event.data && event.data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.keys().then((names) => {
                return Promise.all(names.map((name) => caches.delete(name)));
            })
        );
    }
});
