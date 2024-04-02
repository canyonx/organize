//lors de l'installation
self.addEventListener('install' ,evt =>{
    evt.waitUntil(
        caches.open('organize').then(function(cache) {
            return cache.addAll([
                'images/icons/android-chrome-192x192.png'
            ]);
        })
    );

}) 


//capture des events
self.addEventListener('fetch' ,evt =>{
    console.log('events captures : ');
    console.log('fetch evt sur url' ,evt.request.url);
    
  evt.respondWith(
    caches.match(evt.request)
    .then(function(response) {
      return response || fetch(evt.request);
    })
    .catch(function() {
      console.log('no cache');
    })
  );
})    