// sw.js

// Listen for push events
self.addEventListener('push', function(event) {
    console.log('Push received');

    let data = {};
    if (event.data) {
        try {
            data = event.data.json(); // get payload from Laravel
        } catch (e) {
            console.error('Push event data error:', e);
        }
    }

    const options = {
        body: data.body || 'You have a new notification!',
        icon: data.icon || '/icon-192x192.png',
        badge: data.badge || '/icon-192x192.png', // optional small icon
        data: {
            url: data.url || '/' // URL to open on click
        },
        actions: [
            {
                action: 'open_url',
                title: 'View',
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'New Notification', options)
    );
});

// Handle notification clicks
self.addEventListener('notificationclick', function(event) {
    console.log('Notification clicked');
    event.notification.close();

    let url = event.notification.data.url;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (let client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
