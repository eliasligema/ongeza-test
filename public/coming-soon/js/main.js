jQuery(document).on('ready', function ($) {
    "use strict";


    /*--------------------------
        PUSH CONTENT OPEN COLOSE
    ---------------------------*/

    var $content = jQuery('.right-details-content');
    jQuery('.info-button').on('click', function () {
        $content.addClass('content-open');
        return false;
    });
    jQuery('.push-content-close').on('click', function () {
        $content.removeClass('content-open');
    });

    var $contentContact = jQuery('.left-contact-content');
    jQuery('.contact-button').on('click', function () {
        $contentContact.addClass('content-open');
        return false;
    });
    jQuery('.push-content-close').on('click', function () {
        $contentContact.removeClass('content-open');
    });
    $content.css({
        'overflow-x': 'hidden',
        'overflow-y': 'scroll'
    });
    $contentContact.css({
        'overflow-x': 'hidden',
        'overflow-y': 'scroll'
    });


    /*--------------------------
        SMOOTH SCROLL
    ----------------------------*/
    jQuery(".right-details-content , .left-contact-content").niceScroll({
        cursorwidth: "0px"
    });


    /*-------------------------------
        COWNDOWN TIMER
    --------------------------------*/
    jQuery('.clock-countdown').downCount({
        date: jQuery('.site-config').attr('data-date'),
        offset: +10
    }, function () {
        //callback here if finished
        //alert('YES, done!');
    });


    /*---------------------------
        MICHIMP INTEGRATION
    -----------------------------*/
    jQuery('#mc-form').ajaxChimp({
        url: 'http://devitfamily.us14.list-manage.com/subscribe/post?u=a77a312486b6e42518623c58a&amp;id=8e9f692d44', //Set Your Mailchamp URL
        callback: function (resp) {
            if (resp.result === 'success') {
                jQuery('.subscriber-form input, .subscriber-form button').hide();
            }
        }
    });


    /*------------------------------
        GALLEY POPUP
    -------------------------------*/
    jQuery('.single-gallery a').magnificPopup({
        type: 'image',
        removalDelay: 500, //delay removal by X to allow out-animation
        callbacks: {
            beforeOpen: function () {
                // just a hack that adds mfp-anim class to markup 
                this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        gallery: {
            enabled: true
        },
        closeOnContentClick: true,
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

}(jQuery));



jQuery(window).on('load', function () {
    "use strict";
    /*--------------------------
        PRE LOADER
    ----------------------------*/
    jQuery(".preeloader").fadeOut(1000);
});
