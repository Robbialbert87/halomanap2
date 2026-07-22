const CACHE_NAME = 'halo-manap-v2';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll([
                '/manifest.json',
                '/pwa-icons/icon-192.svg',
                '/pwa-icons/icon-512.svg',
            ]);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    if (event.request.method !== 'GET') {
        return;
    }

    if (url.pathname === '/login' || url.pathname === '/lacak' || url.pathname.startsWith('/pengaduan')) {
        event.respondWith(fetch(event.request));
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cached) => {
            return fetch(event.request).then((response) => {
                if (response.ok) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            }).catch(() => {
                return cached || new Response('Offline', { status: 503 });
            });
        })
    );
});
