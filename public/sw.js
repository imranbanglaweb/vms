// self.addEventListener('push', function (event) {
//     const data = event.data.json();

//     event.waitUntil(
//         self.registration.showNotification(data.title, {
//             body: data.body,
//             icon: data.icon,
//             data: data.data
//         })
//     );
// });

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data.url || '/';
    event.waitUntil(clients.openWindow(url));
});

