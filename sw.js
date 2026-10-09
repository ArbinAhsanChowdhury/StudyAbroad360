// sw.js - Service Worker for StudyAbroad360 Web Push Notifications

self.addEventListener('push', function(event) {
    let data = { title: 'StudyAbroad360 Deadline Alert', body: 'You have an upcoming application deadline!', url: '/student_dashboard.php' };
    
    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: '/assets/images/logo.png',
        badge: '/assets/images/badge.png',
        vibrate: [200, 100, 200, 100, 200],
        data: {
            url: data.url || '/student_dashboard.php'
        },
        actions: [
            { action: 'open_dashboard', title: 'Open Dashboard' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const targetUrl = event.notification.data ? event.notification.data.url : '/student_dashboard.php';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (let i = 0; i < clientList.length; i++) {
                let client = clientList[i];
                if (client.url.includes('student_dashboard.php') && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
