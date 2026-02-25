const CACHE_NAME = 'ayosehat-v1';
const OFFLINE_URL = '/offline';

// Assets to cache on install
const PRECACHE_ASSETS = [
  '/',
  '/manifest.json',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
];

// Install event — precache essential assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[SW] Precaching app shell');
      return cache.addAll(PRECACHE_ASSETS);
    })
  );
  self.skipWaiting();
});

// Activate event — clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((name) => name !== CACHE_NAME)
          .map((name) => {
            console.log('[SW] Deleting old cache:', name);
            return caches.delete(name);
          })
      );
    })
  );
  self.clients.claim();
});

// Fetch event — network first, fallback to cache
self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Skip non-GET requests (form submissions etc.)
  if (request.method !== 'GET') return;

  // Skip external requests
  if (!request.url.startsWith(self.location.origin)) return;

  // Skip API/auth routes from caching
  if (request.url.includes('/login') ||
      request.url.includes('/register') ||
      request.url.includes('/logout')) {
    return;
  }

  event.respondWith(
    // Network first strategy
    fetch(request)
      .then((response) => {
        // Cache successful responses
        if (response.ok) {
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseClone);
          });
        }
        return response;
      })
      .catch(() => {
        // Fallback to cache
        return caches.match(request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }
          // If navigating and no cache, show offline page
          if (request.mode === 'navigate') {
            return caches.match('/').then((homeCache) => {
              return homeCache || new Response(
                `<!DOCTYPE html>
                <html lang="id">
                <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Ayo Sehat - Offline</title>
                  <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: 'Inter', system-ui, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
                    .card { background: white; border-radius: 24px; padding: 48px 32px; text-align: center; max-width: 400px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
                    .icon { font-size: 64px; margin-bottom: 16px; }
                    h1 { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
                    p { color: #64748b; font-size: 14px; margin-bottom: 24px; }
                    button { background: #f97316; color: white; border: none; padding: 14px 32px; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: background 0.2s; }
                    button:hover { background: #ea580c; }
                  </style>
                </head>
                <body>
                  <div class="card">
                    <div class="icon">📡</div>
                    <h1>Kamu Offline</h1>
                    <p>Koneksi internet tidak tersedia. Coba lagi nanti untuk melihat data terbaru.</p>
                    <button onclick="location.reload()">Coba Lagi</button>
                  </div>
                </body>
                </html>`,
                { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
              );
            });
          }
          return new Response('', { status: 408 });
        });
      })
  );
});
