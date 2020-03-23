<style>
.toast-container {
    position: fixed;
    bottom:0;
    right:0;
    width: 250px;
    z-index: 9999;
}

.toast-container:empty {
    display: none;
}

.toast-container .toast {
    margin:10px;
}

.toast .close {
    font-size: 1.2rem;
    font-weight: normal;
    line-height: 0.5;
}
</style>
<div class="toast-container" id="toast-container"></div>
<script src="{{ url('js/database-handler.js') }}"></script>
<script src="{{ url('js/worker-register.js') }}"></script>
@if(in_array(request()->getHost(), ["localhost", "project.co"])) 
<script src="{{config('backend.local-urls.socket-script')}}"></script>
@else
<script src="{{config('backend.urls.socket-script')}}"></script>
@endif
<script>
var RegisterWorkerActive = null;
const NotificationDatabase = CustomDatabase("notification",{version:1,tables:[{name:"message",key:"id"}]});
@if(session('logout'))
(async function() {
    console.log('delete')
    await NotificationDatabase.table("message").clear();
})();
@endif

@if(Auth::check())
const privateNamespace = '{{ user('user_id') }}';
@else
const privateNamespace = 'xxx';
@endif
@if(in_array(request()->getHost(), ["localhost", "project.co"])) 
const socketServer = '{{ config('backend.local-urls.socket-io') }}';
@else
const socketServer = '{{ config('backend.urls.socket-io') }}';
@endif
const socket = io.connect(socketServer);
async function  reloadNotification()  {
    var container = document.querySelector('.toast-container');
    var data = await NotificationDatabase.table("message").find();
    data.sort(function(a,b){
        return new Date(a.timestamp) - new Date(b.timestamp);
    });
    container.innerHTML = '';

    for (const row of data) {
        socket.emit('notification-confirm', {id:row.id});
        addNotification(row);
    }
}

function  updateNotification(data)  {
    // console.log(data)
    window.page_notification = data.notification;
    var badges = document.querySelectorAll('.badge-notification');
    badges.forEach(function(elem){ 
        elem.innerHTML = (window.page_notification > 0)? window.page_notification: '';
    })
}

function addNotification(data) {
    if(!(data.title && data.message)) return;

    var container = document.querySelector('.toast-container');
    var elem = document.createElement('DIV');
    elem.setAttribute("class", "toast show bg-dark text-light");
    elem.setAttribute("role", "alert");
    elem.setAttribute("aria-live", "assertive");
    elem.setAttribute("aria-atomic", true);
 
    var temp = `
    <div class="toast-body">
        <button type="button" class="close" data-dismiss="toast" aria-label="Close" data-close="${data.id}">
            <span aria-hidden="true">&times;</span>
        </button>
        <a href="${data.link}" data-close="${data.id}" class="font-weight-bold"><i class="fa fa-exclamation-circle mr-1"></i> ${data.title}</a>
        <a href="${data.link}" data-close="${data.id}" class="d-block small">${clearHtml(data.message)}</a>
    </div>`;
    elem.innerHTML = temp;
    container.appendChild(elem);
    setTimeout(function() {
        NotificationDatabase.table("message").delete(data.id);
        elem.remove();
    }, 20000);
    elem.querySelectorAll('[data-close]').forEach(function(close){ 
        close.addEventListener("click", function(event){
            event.preventDefault();
            NotificationDatabase.table("message").delete(this.dataset.close);
            elem.remove();
            if(close.href) {
                setTimeout( function(){ window.location = close.href;}, 1000);
            }
        });
    });
}

socket.on('message', function (data) {
    console.log(data);
    if(data.type == 'pop') {
        NotificationDatabase.table("message").create(data);
        reloadNotification();
    } else if(data.type == 'reload') {
        updateNotification(data);
    }
});

@if(Auth::check())
var _privateSocket = null;
var _socketS = socketServer;
var _privateN = privateNamespace;

async function connectPrivate(namespace) {
    if(_privateSocket == null) {
        _privateSocket = io.connect(`${_socketS}/${namespace}`);
        _privateSocket.on('notification', function (data) {
            // console.log(data)
            if(data.type == 'pop') {
                NotificationDatabase.table("message").create(data);
                console.log(RegisterWorkerActive)
                if(RegisterWorkerActive) {
                    data.message_text = clearHtml(data.message);
                    RegisterWorkerActive.postMessage(JSON.stringify(data));
                } else {
                    systemNotification(data);
                }
                
                reloadNotification();
            } else if(data.type == 'reload') {
                updateNotification(data)
            }
        });
    }
}
 
socket.on('private', function (namespace) {
    // console.log(namespace)
    if(namespace === _privateN) {
        connectPrivate(namespace)
    }
});
@endif

jQuery(function(){
    reloadNotification();
});


</script>
