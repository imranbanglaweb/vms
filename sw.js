self.addEventListener('push', function (event) {

    if (!event.data) {
        console.log('Push event but no data');
        return;
    }

    const data = event.data.json();

    const options = {
        body: data.body || 'New notification',
        icon: data.icon || '/icon-192x192.png',
        badge: '/icon-192x192.png',
        data: data.data || {}
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Notification', options)
    );
});
