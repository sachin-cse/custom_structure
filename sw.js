const version = 'v1::static';
    
    self.addEventListener('install', e => {
        e.waitUntil(
            caches.open(version).then(cache => {
                return cache.addAll([
                    '/skeleton/code/','/skeleton/code/templates/default/css/plugins.min.css','/skeleton/code/templates/default/css/style.min.css','/skeleton/code/templates/default/css/responsive.min.css','/skeleton/code/templates/default/js/plugins.min.js','/skeleton/code/templates/default/js/custom.min.js'
                ]).then(() => self.skipWaiting());
            })
        );
    });
    
    self.addEventListener("fetch", function (event) {
        console.log('WORKER: fetch event in progress.');
    
        if (event.request.method !== 'GET') {
    
            return;
        }
        event.respondWith(
            caches
            .match(event.request)
            .then(function (cached) {
                var networked = fetch(event.request)
    
                    .then(fetchedFromNetwork, unableToResolve)
    
                    .catch(unableToResolve);
    
    
                return cached || networked;
    
                function fetchedFromNetwork(response) {
    
                    var cacheCopy = response.clone();
                    caches
                        .open(version + 'pages')
                        .then(function add(cache) {
                            cache.put(event.request, cacheCopy);
                        })
                        .then(function () {
                            console.log('WORKER: fetch response stored in cache.', event.request.url);
                        });
                    return response;
                }
    
                function unableToResolve() {
    
                    console.log('WORKER: fetch request failed in both cache and network.');
    
                    return new Response('<h1>Oops! Service unavailable. Please check your internet connection.</h1>', {
                        status: 503,
                        statusText: 'Service Unavailable',
                        headers: new Headers({
                            'Content-Type': 'text/html'
                        })
                    });
                }
            })
        );
    });
    self.addEventListener("activate", function (event) {
        console.log('WORKER: activate event in progress.');
        event.waitUntil(
            caches
            .keys()
            .then(function (keys) {
                return Promise.all(
                    keys
                    .filter(function (key) {
    
                        return !key.startsWith(version);
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    })
                );
            })
            .then(function () {
                console.log('WORKER: activate completed.');
            })
        );
    });