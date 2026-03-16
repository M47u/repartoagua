const CACHE_NAME = 'aguas-litoral-v1';

// Recursos estáticos a cachear al instalar el SW
const STATIC_ASSETS = [
    '/',
    '/images/logo-aguas-del-litoral.png',
    '/manifest.json'
];

// Instalar: cachear recursos estáticos
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(STATIC_ASSETS))
    );
    self.skipWaiting();
});

// Activar: eliminar caches viejas
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

// Fetch: estrategia Network First con fallback a cache
self.addEventListener('fetch', event => {
    // Solo interceptar peticiones GET
    if (event.request.method !== 'GET') return;

    // No interceptar peticiones a APIs externas (Mapbox, etc.)
    const url = new URL(event.request.url);
    if (url.hostname !== self.location.hostname) return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Cachear respuestas exitosas de recursos estáticos
                if (response.ok && (
                    event.request.url.match(/\.(png|jpg|jpeg|svg|ico|css|js|woff2?)(\?|$)/)
                )) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(() => {
                // Sin red: intentar desde cache
                return caches.match(event.request).then(cached => {
                    if (cached) return cached;
                    // Para páginas HTML sin conexión, mostrar la raíz cacheada
                    if (event.request.headers.get('accept')?.includes('text/html')) {
                        return caches.match('/');
                    }
                });
            })
    );
});
