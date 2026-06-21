const CACHE_NAME = 'berkah-mulia-cache-v3';
const urlsToCache = [
  '/',
  '/favicon.ico',
  '/logo.webp',
  '/logo.jpeg'
];

// Install Event - Pre-cache core assets & skip waiting for immediate activation
self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Activate Event - Take control of clients immediately and clean up old cache versions
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      let cacheDeleted = false;
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('[Service Worker] Deleting old cache:', cacheName);
            cacheDeleted = true;
            return caches.delete(cacheName);
          }
        })
      ).then(() => {
        return self.clients.claim().then(() => {
          if (cacheDeleted) {
            // Beri tahu halaman (client) untuk reload karena cache telah dibersihkan
            return self.clients.matchAll().then(clients => {
              clients.forEach(client => {
                client.postMessage({ type: 'CACHE_CLEARED' });
              });
            });
          }
        });
      });
    })
  );
});

// Fetch Event - Handle requests with caching strategies based on destination/type
self.addEventListener('fetch', event => {
  // Only handle GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  const url = new URL(event.request.url);

  // Bypass service worker caching completely for admin panel routes
  if (url.pathname.startsWith('/admin')) {
    return;
  }

  // Determine caching strategy
  const isHtml = event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html');
  
  if (isHtml) {
    // 1. Network-First Strategy for HTML pages (homepage, catalog, etc.)
    // Ensures users always see the latest content when online, falling back to cache if offline.
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Cache the fresh page response
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, responseClone);
          });
          return response;
        })
        .catch(() => {
          // If offline, retrieve from cache
          return caches.match(event.request);
        })
    );
  } else {
    // 2. Cache-First Strategy for static assets (images, fonts, stylesheets, JS)
    // Minimizes load times for static files. Unique hashes in filenames (Vite) handle updates.
    event.respondWith(
      caches.match(event.request)
        .then(cachedResponse => {
          if (cachedResponse) {
            return cachedResponse;
          }
          return fetch(event.request).then(response => {
            // Only cache valid successful GET responses
            if (response && response.status === 200 && response.type === 'basic') {
              const responseClone = response.clone();
              caches.open(CACHE_NAME).then(cache => {
                cache.put(event.request, responseClone);
              });
            }
            return response;
          });
        })
    );
  }
});
