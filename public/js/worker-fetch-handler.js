const $self = self;
self.addEventListener('message', function(event){
    var data = JSON.parse(event.data);

    console.log("SW Received Message:");
    console.log(data);

    self.id = data.id;
    self.socketServer = data.socketServer;
});

async function systemNotification(data) {
    var permission = Notification.permission;
    console.log(permission)

    if(permission == "granted") {
        self.registration.showNotification(data.title, {
            actions: [
                {
                    action: 'show-action',
                    title: 'Open',
                },
            ],
            badge: "",
            body: data.message,
            data: null,
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

var loading = false;
async function init_process(socketServer, id) {
    console.log("Process init")
    if(!loading) {
        loading = true;
        // var fetch_url = `${socketServer}/service-worker/${id}`;
        var fetch_url = `http://localhost/projects/mjuzi/js/data.json`;
        var responce = await fetch(fetch_url, {
            "method":"GET",
            "headers": { "content-type":"application/json" }
        }).then((res) => {
            console.log(res)
            return res.json();
        });

        if(Array.isArray(responce)) {
            for (const row of responce) {
                systemNotification(row);
            }
        }

        loading = false;
    }
}

setInterval(function(){
    if($self.socketServer || true) {
        // init_process($self.socketServer, $self.id);
    }
}, 15000)
