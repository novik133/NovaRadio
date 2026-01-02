const CACHE = 'novaradio-v1';
const ASSETS = ['/', '/assets/css/style.css', '/assets/js/app.js', '/assets/img/placeholder.png'];

self.addEventListener('install', e => e.waitUntil(caches.open(CACHE).then(c => c.addAll(ASSETS))));
self.addEventListener('fetch', e => {
    if (e.request.url.includes('api.php') || e.request.url.includes('ajax.php')) return;
    e.respondWith(caches.match(e.request).then(r => r || fetch(e.request)));
});
