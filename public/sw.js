const CACHE_NAME = 'phonebd-v1';
const urlsToCache = [
    '/',
    '/images/logo.png',
    '/images/pwa.png',
    '/images/favicon.ico',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', event => {
    // For navigation requests (HTML pages), try network first, then cache
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.match(event.request);
                })
        );
    } else {
        // For other requests (images, css, js), try cache first, then network
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request);
                })
        );
    }
});
