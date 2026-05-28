const CACHE_NAME = 'kembaran-ngadu-v3';
const urlsToCache = [
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME && cache !== 'kembaran-ngadu-assets') {
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  
  // Cache strategy for images
  if (event.request.destination === 'image' || url.pathname.includes('/storage/')) {
    event.respondWith(
      caches.open('kembaran-ngadu-assets').then(cache => {
        return cache.match(event.request).then(response => {
          const fetchPromise = fetch(event.request).then(networkResponse => {
            cache.put(event.request, networkResponse.clone());
            return networkResponse;
          });
          return response || fetchPromise;
        });
      })
    );
    return;
  }

  // Network First for HTML and dynamic content (including '/')
  event.respondWith(
    fetch(event.request)
      .catch(() => caches.match(event.request))
  );
});

