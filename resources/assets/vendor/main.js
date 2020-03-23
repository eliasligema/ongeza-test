jQuery(function(){
    jQuery('[data-toggle="tooltip"]').tooltip();

    jQuery('.pics').cycle({
        fx:     'fade',
        speed:  'fast',
        timeout: 0,
        next:   '#next2',
        prev:   '#prev2'
    });
});

window.addEventListener('load', setMenu, false);
window.addEventListener('resize', setMenu, false);

function setMenu(){
    var menu_height = $(window).height()-50;
    jQuery(".sidebar-scroll").slimScroll({
        height: menu_height,
        allowPageScroll: false,
        wheelStep:5,
        color: '#000'
    });
}

