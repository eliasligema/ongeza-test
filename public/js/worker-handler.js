const $self = self;
self.addEventListener('message', function(event){
    var data = JSON.parse(event.data);

    systemNotification(data);
});

self.addEventListener('notificationclick', (event) => {
    console.log(event);
    if (event.action === 'close') {
        event.notification.close();
    } else if(event.action === 'open') {
        // console.log(clients);
        // if (clients.openWindow) {
        //     event.waitUntil(
        //         clients.openWindow(event.notification.data.link)
        //     );
        // }
        clients.matchAll().then(function(clis) {
            var client = clis.find(function(c) {
                c.visibilityState === 'visible';
            });
            if (client !== undefined) {
                client.navigate(event.notification.data.link);
                client.focus();
            } else {
                clients.openWindow(event.notification.data.link);
                event.notification.close();
            }
        });
    } else {
      console.log('handle notification click');
    }
}, false);

async function systemNotification(data) {
    var permission = Notification.permission;
    console.log(permission)

    if(permission == "granted") {
        self.registration.showNotification(data.title, {
            actions: [
                {
                    action: 'open',
                    title: 'Open',
                    link: data.link,
                },
            ],
            badge: "",
            body: data.message_text,
            data: data,
            dir: "auto",
            icon: "https://mjuzi.africa/img/favicon.png",
            image: "https://mjuzi.africa/img/bg-home-02.jpg",
            lang: "",
            onclick: null,
            onclose: null,
            onerror: null,
            onshow: null,
            renotify: false,
            requireInteraction: false,
            silent: false,
            tag: "",
        })
    }
}

