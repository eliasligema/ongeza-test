// Register SW, Register Push, Send notification
async function registerWorker() {
    var permission = Notification.permission;
    if(permission != "granted") {
        var permission = await Notification.requestPermission();
    }

    const register = await navigator.serviceWorker.register(`${base_url}/js/worker-handler.js`);//https://project.co/mjuzi
    // const register = await navigator.serviceWorker.register(`http://project.co/mjuzi/js/worker-handler.js`);//https://project.co/mjuzi

    var timer = setInterval(function() {
        if(register.active) {
            RegisterWorkerActive = register.active;
            console.log(RegisterWorkerActive)
            clearInterval(timer);
        }
    }, 500);
}

// Check for service worker
if('serviceWorker' in navigator) {
    registerWorker().catch((err)=>console.log(err))
}


function clearHtml(html) {
    var div = document.createElement("div");
    div.innerHTML = html;
    var text = div.textContent || div.innerText || "";

    return text;
}

async function systemNotification(data) {
    var permission = Notification.permission;
    if(permission != "granted") {
        permission = await Notification.requestPermission();
    }

    if(permission == "granted") {
        var notification = new Notification(data.title, {
            badge: "",
            body: clearHtml(data.message),
            data: null,
            dir: "auto",
            icon: `${base_url}/img/favicon.png`,
            image: `${base_url}/img/bg-home-02.jpg`,
            lang: "",
            onclose: null,
            onerror: null,
            onshow: null,
            renotify: false,
            requireInteraction: false,
            silent: false,
            tag: "",
        });

        notification.onclick = function () {
            window.open(data.link);
        };
    }
}