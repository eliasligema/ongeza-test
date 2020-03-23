var atr = atr || {};

atr.components = atr.components || {};


var atr = atr || {};

//***********************************************************************************************
// This namespace handles all interactions with menus - the root menu and the sub menus
//***********************************************************************************************
atr.common = (function() {

    //var _dragging;

    var elementsShowing = true;
    var lastScrollTop = 0;
    var scrollDirection = 'down';
    
    var _userIsOnMobile;
    var _userIsOnTablet;
    var _userIsOniPad;

    //****************************************************************************
    // Use this method to write to the console so it can be disabled in one place
    //****************************************************************************
    var _writeToConsole = function (msg) {
        if(atr.debug.writeToConsoleForAtrCommon === true) console.log(msg);
    };

    // ref: http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript?noredirect=1&lq=1
    // query string: ?foo=lorem&bar=&baz
    // var foo = getParameterByName('foo'); // "lorem"
    var _getQueryStringParameterByName = function(name, url) {
        if (!url) {
            url = window.location.href;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    };

    //****************************************************************************
    // determine's whether the user is on a mobile device by looking at the user agent string
    // determine's if it is a tablet based on the screen resolution.
    //****************************************************************************
    var initUserAgentDetails = function () {
        //_writeToConsole('initUserAgentDetails started');

        //******************************************************************************************************
        // We are determining whether the user is on a mobile by looking at the browser User Agent, as follows
        //
        //  - contains 'Android' AND 'mobile'
        //  - contains 'iPhone' or 'iPod'
        //  - contains 'IEMobile'
        //
        //******************************************************************************************************

        //_writeToConsole("navigator.userAgent.match(/Android/i) == 'Android' = " + (navigator.userAgent.match(/Android/i) == 'Android').toString());
        //_writeToConsole("navigator.userAgent.match(/Mobile/i) == 'Mobile' = " + (navigator.userAgent.match(/Mobile/i) == 'Mobile').toString());
        //_writeToConsole("navigator.userAgent.match(/iPhone/i) == 'iPhone' = " + (navigator.userAgent.match(/iPhone/i) == 'iPhone').toString());
        //_writeToConsole("navigator.userAgent.match(/iPad/i) == 'iPad' = " + (navigator.userAgent.match(/iPod/i) == 'iPod').toString());
        //_writeToConsole("navigator.userAgent.match(/IEMobile/i) == 'IEMobile' = " + (navigator.userAgent.match(/IEMobile/i) == 'IEMobile').toString());

        _userIsOnMobile = (navigator.userAgent.match(/Android/i) == 'Android' && navigator.userAgent.match(/Mobile/i) === 'Mobile')
            || (navigator.userAgent.match(/iPhone/i) === 'iPhone' || navigator.userAgent.match(/iPod/i) === 'iPod')
            || (navigator.userAgent.match(/IEMobile/i) === 'IEMobile');

        //_writeToConsole('initUserAgentDetails: userIsOnMobile = ' + _userIsOnMobile);

        //******************************************************************************************************
        // We are determining whether the user is on a tablet by looking at the browser User Agent, as follows
        //
        //  - contains 'Android' AND NOT 'mobile'
        //  - contains 'iPad'
        //  - contains 'MSIE' AND 'Touch' AND NOT 'IEMobile' (IE10)
        //
        //******************************************************************************************************
        //_writeToConsole("navigator.userAgent.match(/Android/i) == 'Android' = " + (navigator.userAgent.match(/Android/i) == 'Android').toString());
        //_writeToConsole("navigator.userAgent.match(/Mobile/i) != 'Mobile' = " + (navigator.userAgent.match(/Mobile/i) != 'Mobile').toString());
        //_writeToConsole("navigator.userAgent.match(/iPad/i) == 'iPad' = " + (navigator.userAgent.match(/iPad/i) == 'iPad').toString());
        //_writeToConsole("navigator.userAgent.match(/MSIE/i) == 'MSIE' = " + (navigator.userAgent.match(/MSIE/i) == 'MSIE').toString());
        //_writeToConsole("navigator.userAgent.match(/Touch/i) == 'Touch' = " + (navigator.userAgent.match(/Touch/i) == 'Touch').toString());
        //_writeToConsole("navigator.userAgent.match(/IEMobile/i) != 'IEMobile' = " + (navigator.userAgent.match(/IEMobile/i) != 'IEMobile').toString());

        _userIsOnTablet = (navigator.userAgent.match(/Android/i) === 'Android' && navigator.userAgent.match(/Mobile/i) !== 'Mobile')
            || (navigator.userAgent.match(/iPad/i) === 'iPad')
            || (navigator.userAgent.match(/MSIE/i) === 'MSIE' && navigator.userAgent.match(/Touch/i) === 'Touch' && navigator.userAgent.match(/IEMobile/i) !== 'IEMobile');

        _userIsOniPad = (navigator.userAgent.match(/iPad/i) === 'iPad');

        //_writeToConsole('initUserAgentDetails: userIsOnTablet = ' + _userIsOnTablet);

        // Set a cookie so the server can read it if necessary
        var userDevice = _userIsOnMobile === true ? 'mobile' : _userIsOnTablet === true ? 'tablet' : 'desktop';
        var userDeviceCookieName = 'ATRUserDevice';

        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000; // cookie lasts an hour
        now.setTime(time);

        $.cookie(userDeviceCookieName, userDevice, { expires: now, path: '/' });
    }

    var userIsOnMobile = function () { return _userIsOnMobile; }

    var userIsOnTablet = function () { return _userIsOnTablet; }

    var userIsOnDesktop = function() { return _userIsOnMobile === false && _userIsOnTablet === false; }

    var userIsOnIPad = function () { return _userIsOniPad; }

    var isPortrait = function() {
        return window.innerHeight > window.innerWidth;
    }

    var _searchBoxHasFocus = false;
    var searchBoxHasFocus = function () { return _searchBoxHasFocus; }

    //****************************************************************************
    // applying the 'removed' class to elements with the 'removable' class should
    // move the elements to their 'off-screen' positions, using css transitions.
    //*****************************************************************************
    var removeElements = function() {
        $('.removable').addClass('removed');
    };

    //****************************************************************************
    // resetting elements with the 'removable' class should
    // move the elements to their 'on-screen' positions, using css transitions.
    //*****************************************************************************
    var resetRemovableElements = function () {
        $('.removable').removeClass('removed');
    };

    var toggleRemovableElements = function() {
        if (elementsShowing) {
            removeElements();
        } else {
            resetRemovableElements();
        }

        elementsShowing = !elementsShowing;
    };

    var throb;

    var initThrobber = function () {

        throb = new Throbber({
            color: '#a3a3a3',
            padding: 50,
            size: 100,
            fade: 200,
            clockwise: true
        });

        throb.appendTo(document.getElementById('ThrobberContainer'));
    }

    var startThrobber = function () {
        try {

            if ($('#main-menu').hasClass('active')) {
                return;
            }

            $('#ThrobberContainer').fadeIn();

            throb.start();
        } catch (e) {
            ////_writeToConsole(e.message);
        }
    };

    var stopThrobber = function() {
        try {
            $('#ThrobberContainer').fadeOut(function() {
                if (throb !== undefined) {
                    throb.stop();
                }
            });

        } catch (e) {
            ////_writeToConsole(e.message);
        }
    };

    var initCustomDropdowns = function() {
        $('body').on('click', '.styled-select-background', function () {
            ////_writeToConsole('styled-select-background clicked');
            var $this = $(this);
            var c = $this.closest('.styled-select-container');
            if (c) {
                var s = c.find('select');
                if (s) openDropdown(s);
                //else {
                    ////_writeToConsole('s not found');   
                //}
            }
            //else {
                ////_writeToConsole('c not found');
            //}
        });

        //$('body').on('blur', '.styled-select-container select', function () {
        //    ////_writeToConsole('styled-select-container lost focus');
        //    var $this = $(this);
        //    $this.attr('size', '1');
        //});
    };

    //***************************************************************************
    //  This function resizes the slideshow container to the height of the window.
    //***************************************************************************
    var resizeBackground = function() {
        //_writeToConsole('resizeBackground: started');

        //var fs = $('#slideshow');
        
        var originalWidth = window.innerWidth;

        // This is a kludge. We need to wait until the orientation has changed so the winow.height value relates to the new orientation.
        // There doesn't seem to be an 'orientationchanged' event.
        setTimeout(function () {

            _writeToConsole('resizeBackground is setting the height of the main viewport');
            $('.main-viewport').height($(window).innerHeight() - 1);


            if (window.innerHeight > window.innerWidth && window.innerWidth !== originalWidth) {
                // The orientation has changed to portrait.
                // We need to return the user to the top of the page and display the banner and titles.
                _writeToConsole('orientation is Portrait. window height = ' + $(window).height());

                //fs.height($(window).height());
                window.scrollTo(0, 0);

                //if ($('#blackboard').hasClass('active')) {
                //    atr.interim.setBlackboardHeight();
                //} else {
                //    $('#blackboard').css('height', '');
                //}

                $('.fadeable').removeClass('faded');

                $('#slideshow-selector').fadeOut();

                atr.components.banner.initPageBanner();

                //setTimeout(function() {
                //        atr.components.banner.initPageBanner();
                //    },
                //    250);

            } else {
                _writeToConsole('orientation is Landscape. window height = ' + $(window).height());

                if (_userIsOnMobile === true) {

                    // This is an attempt scroll down to try to force the mobile browser to hide its address bars
                    // doesn't seem to work
                    setTimeout(function () {
                            _writeToConsole('resizeBackground is re-setting the height of the main viewport');
                            $('.main-viewport').height($(window).innerHeight() + 1);

                            //_writeToConsole('user is on mobile - scrolling down 100px');
                            //window.scrollTo(0, 100);

                            _writeToConsole('user is on mobile - scrolling up 99px');
                            window.scrollTo(0, 1);

                            //_writeToConsole('resizeBackground is re-setting the height of the main viewport');
                            //$('.main-viewport').height($(window).innerHeight() + 1);

                        },
                        1000);

                }

                //fs.height($(window).height());
                
                //if ($('#blackboard').hasClass('active')) {
                //    atr.interim.setBlackboardHeight();
                //} else {
                //    $('#blackboard').css('height', '');
                //}
            }
            
        }, 500);
    }


    //***************************************************************************
    //
    //  Elements with a class of 'loadable' should have an attribute 'data-url
    //  which specifies a controller method returning a partial view
    //  Using this technique, we can load sections of the page using ajax
    //  while the page appears to have loaded.
    //
    //***************************************************************************
    // OBSOLETE
    var fetchLoadableElements = function() {
        $('.loadable').not('.loaded').each(function () {

            var $this = $(this);
            var url = $this.attr('data-url');

            $.when(
                $.ajax({
                    url: location.origin.toLowerCase() + '/' + url.toLowerCase(),
                    type: 'GET',
                    dataType: 'html'
                })
                .then(function (html) {
                    $this.html(html);

                    // Add the class 'loaded' so that we don't try to load this div again.
                    $this.addClass('loaded');
                }),
                function (data) {
                    //error
                    atr.common.handleAjaxError(data);
                });
        });
    };

    //***************************************************************************
    //
    //  makes a GET call to the server url and inserts the html returned into the panelId element
    //
    //***************************************************************************
    var panelsBeingLoaded = [];
    var loadContentIntoPanel = function(url, panelId, callbackFn) {
        //_writeToConsole('loadContentIntoPanel starting. url = ' + url + '; panelId = ' + panelId);

        // To ensure that the same call is not made more than once at the same time, we keep a tally of which urls are being loaded.
        if (_.some(panelsBeingLoaded, function(p) { return p.url == url; })) {
            return;
        }

        panelsBeingLoaded.push({ 'url': url, 'panelId': panelId });

        $.when(
            $.ajax({
                url: location.origin.toLowerCase() + '/' + url.toLowerCase(),
                type: 'GET',
                dataType: 'html'
            })
            .then(function (html) {
                //_writeToConsole('loadContentIntoPanel returned ' + html.length + ' bytes of data');

                // Remove the current url from the list of panels being loaded
                panelsBeingLoaded = _.reject(panelsBeingLoaded, function(p) { return p.url == url; });

                $('#' + panelId).html(html);
                if (callbackFn) {
                    callbackFn.call();
                }
            }),
            function (data) {
                //error
                atr.common.handleAjaxError(data);
            });
    }

    //***************************************************************************
    //
    //  monitors which direction the user is scrolling and updates the variable 'direction'
    //
    //***************************************************************************
    var initScrollDirection = function() {

        var $window = $(window);

        $window.scroll(function(e) {

            var t = $window.scrollTop();
            scrollDirection = t > lastScrollTop ? 'down' : 'up';
            lastScrollTop = t;
        });

    };

    var getScrollDirection = function () { return scrollDirection; }

    //***************************************************************************
    //
    //  dynamically load page content for the specified url
    //
    //***************************************************************************
    var loadGuidebook = function (url, sectionTitle, inlineLink, callback) {
        if (inlineLink !== undefined && $('#content-text article.opened .inline[data-article="' + sectionTitle + '"]').length) {
            // if the inline content is already loaded, close it
            $('#content-text article.opened .inline[data-article="' + sectionTitle + '"]').remove();
            return;
        }

        atr.common.startThrobber();

        if (url) {
            $.when(
                $.ajax({
                    url: '/locations/loadguidebook/',
                    data: {
                        url: url
                    }
                })
            ).then(function (data) {

                //$.post(partial.Url, function (data) {
                    //success

                    var currTop = 0;
                    var fudgeFactor = 21;

                    if (inlineLink !== undefined) {
                        // inline guidebook article, loaded from an link in the current article
                        // calculate the position of the clicked-on article relative to the top of the browser window
                        currTop = $(inlineLink).offset().top - $(window).scrollTop() + fudgeFactor;

                        // if this inline link is not within other inline content remove all existing inline content from the page first
                        if (!$(inlineLink).closest('.inline').length) {
                            $('#content-text article.opened .inline').remove();
                        }
                        // close any other inline content within the same container as this link before opening this one
                        //$(inlineLink).closest('.guide').find('.inline').remove();

                        // insert the article into the current article text on the line after the link
                        if (!$('#content-text article.opened .inline[data-article="' + sectionTitle + '"]').length) {
                            var placeHolder = '<div class="inline" data-article="' + sectionTitle + '"></div>';
                            // find the first <br> after the link
                            // NOTE: this won't work if the link is nested within more than 1 element within the article, there may be a better way of doing this
                            if ($(inlineLink).parent().hasClass('guide')) {
                                // link isn't nested inside an element
                                if ($(inlineLink).nextAll('br').first().length) {
                                    // insert the new content after the first break after the link
                                    $(inlineLink).nextAll('br').first().after(placeHolder);
                                    // add a style so some padding can be added to the end of the inline content
                                    $(inlineLink).nextAll('.inline').first().addClass('pad');
                                } else {
                                    // there is no break after the link insert the content inside the end of the parent guide div
                                    $(inlineLink).closest('.guide').append(placeHolder);
                                }
                            } else {
                                // link is nested inside another element, usually <b>
                                if ($(inlineLink).parent().nextAll('br').first().length) {
                                    // add the content after the first line break
                                    $(inlineLink).parent().nextAll('br').first().after(placeHolder);
                                    // add a style so some padding can be added to the end of the inline content
                                    $(inlineLink).nextAll('.inline').first().addClass('pad');
                                } else {
                                    // there is no break after the link insert the content at the end of the parent guide div
                                    $(inlineLink).closest('.guide').append(placeHolder);
                                }
                            }
                        }
                        var inlineContent = $('#content-text article.opened .inline[data-article="' + sectionTitle + '"]');
                        if ($(inlineContent).length) {
                            // insert the inline content
                            $(inlineContent).html('<a title="close" href="javascript:void(0)" class="article-close top"></a><a title="close" href="javascript:void(0)" class="article-close bottom"></a>' + data);

                            // scroll the page so that the new article is in the same vertical position within the browser as it was pre-click
                            // so that it doesn't look as if the page has moved
                            // *probably* only need to do this if the link is inside some inline content, ie don't do it for the top-level "menu"
                            // but this may change if the users change the way it works again without any warning
                            //_writeToConsole($(inlineContent).closest('.guide').parent('article').length);


                            //if (currTop != 0 && $(inlineContent).closest('.guide').parent('article').length == 0) {
                            // scroll the page to the selected article 
                            var scrollTo = $(inlineContent).offset().top - currTop;
                            $(document).scrollTop(scrollTo);
                            // }
                        } else {
                            // couldn't insert the inline content for unknown reason, so open the menu instead
                            inlineLink = undefined;
                        }
                    }

                    if (inlineLink == undefined) {
                        // normal guidebook article
                        // calculate the position of the clicked-on article relative to the top of the browser window
                        if ($('#content-text article[data-section-title="' + sectionTitle + '"]').length) {
                            currTop = $('#content-text article[data-section-title="' + sectionTitle + '"]').offset().top - $(window).scrollTop();
                        }
                        // close any open articles
                        $('#content-text article.opened').removeClass('opened').addClass('closed');
                        $('#content-text header.opened').removeClass('opened').addClass('closed');

                        $('#content-text article[data-section-title="' + sectionTitle + '"]').html(data);
                        $('#content-text article[data-section-title="' + sectionTitle + '"]').removeClass('closed').addClass('opened');
                        $('#content-text header[data-section-title="' + sectionTitle + '"]').removeClass('closed').addClass('opened');

                        // set the browser title to the page title for the selected content
                        if ($('#content-text article.opened .JS-LocationTitle').length && $('#content-text article.opened .JS-LocationTitle').html() !== '') {
                            document.title = $('#content-text article.opened .JS-LocationTitle').text();
                        }

                        if (history && history.pushState) {
                            history.pushState({ url: window.location.href }, document.title, url);
                        }

                        // scroll the page so that the new article is in the same vertical position within the browser as it was pre-click
                        // so that it doesn't look as if the page has moved
                        if (currTop != 0) {
                            // scroll the page to the selected article 
                            var scrollTo = $('#content-text article.opened').offset().top - currTop;
                            if (scrollTo < 0) {
                                // scrollTo can be < 0 if loadguidebook is invoked from a link in the page title, and the article being linked to was below the fold
                                $(document).scrollTop($('#content-text header.opened').offset().top - ($('#page-banner').outerHeight(true) * 2) + 50);
                            } else if (scrollTo < $(window).scrollTop()) {
                                $(document).scrollTop(scrollTo);
                            }
                        }
                    }

                    rescaleGuideBookImages();

                    if (atr.common.userIsOnMobile() == true) {
                        removePricesLinkFromGuidebook();
                    }

                    if (callback) callback();

                    atr.common.stopThrobber();
            //    });
            }, function (error) {
                //error
                atr.common.stopThrobber();
                //if (error.responseText != '')
                    //_writeToConsole(error.responseText);
            });
        }
    };

    //***************************************************************************
    // Open the lodge owners website in a new browser window
    //***************************************************************************
    var ownerLink = function (theUrl) {
        var newWindow = window.open(theUrl, 'owner', 'titlebar=yes,toolbar=yes,menubar=yes,scrollbars=yes,resizable=yes,width=1150,height=700,left=100,top=100');
        try {
            newWindow.focus();
        } catch (ex) {
        }
    };

    var popup = function(theUrl, winName, features) {
        var newWindow = window.open(theUrl, winName, features);
        try {
            newWindow.focus();
        } catch (ex) {
        }
    };

    //***************************************************************************
    // Open the specified content menu item
    //***************************************************************************
    var openContentPage = function(page) {
        $('#content-text .menu a').each(function () {
            if ($(this).data('pageurl') == page) {
                if (!$(this).parent().parent().hasClass('opened')) {
                    // page not open
                    $(this).click();
                } else {
                    // page already open
                    $('html, body').animate({ scrollTop: $(this).offset().top - ($('#page-banner').outerHeight(true) * 2) + 50 }, 'medium');
                }
                return;
            }
        });
    };

    var initSearchHandler = function () {
        //===========================================
        //============== search handler =============
        //===========================================

        // setup the event handlers for any search boxes in the page
        // input box must specify the following data items...
        // data-search-flags= locations=1 areas=2 resources=3 sales persons=4 web pages=5 experience=6 trips=7 
        // data-callback="name of even handler" eg atr.menu.handleMapSearchClick
        $(document).on('focus', '.search-box input', function (e) {
            e.preventDefault();
            e.stopPropagation();

            _searchBoxHasFocus = true;

            
            // When the user is searching, we need to apply a class to the banner
            // so we can style it in mobile landscape mode (for Android)
            $(this).addClass('searching');
            $('body').addClass('searching');
            _writeToConsole('atr.common 576 - searching added to body');
            
            return false;
        });
        $(document).on('keyup', '.search-box input', function (event) {
            atr.menu.searchHandler(event, $(this), $(this).parent().find('.search-results'), $(this).data('search-flags'), 400);
            event.stopPropagation();
        }).on('keydown', '.search-box input', function (event) {
            
            // up/down/enter keys can be used to navigate the results list
            atr.menu.searchKeyPressed(event, $(this), $(this).parent().find('.search-results'), eval($(this).data('callback')));
            event.stopPropagation();
        }).on('blur', '.search-box input', function (e) {
            
            var $this = $(this);
            setTimeout(function () {
                // for unknown reason blur is sometimes triggered while scrolling the search results even though input box has the focus
                if (document.activeElement.id == '' || (document.activeElement.id != $this.prop('id'))) {

                    if ($('#atr-main-menu-wrapper').hasClass('active')) return;

                    _searchBoxHasFocus = false;

                    $('#page-banner').removeClass('searching');
                    $this.removeClass('searching');
                    $('body').removeClass('searching');
                    _writeToConsole('atr.common 608 - searching removed from body');

                    //_writeToConsole('search box has lost focus');

                    if ($this.hasClass('display-selection')) {
                        $this.parent().find('.search-results').hide();
                    } else {
                        $this.val('').parent().find('.search-results').hide();
                    }
                };
            }, 300);
        });

        $(document).on('click', function(e) {

            var $target = $(e.target);
            _writeToConsole('$target id = ' + $target[0].id + '; className = ' + $target[0].className);
            ////_writeToConsole($target[0]);

            ////_writeToConsole('$target.closest search box: ' + $target.closest('.search-box').length);

            if ($target.closest('.search-box').length === 0) {

                _searchBoxHasFocus = false;

                $('#page-banner').removeClass('searching');
                $('.search-box input').removeClass('searching');

                ////_writeToConsole('search box has lost focus');
            } 
            

            

            if ($target.closest('#slideshow-selector').length == 0 && $target.closest('#slideshow-album-selector').length == 0 && $('#slideshow-selector').css('display') == 'block') {
                $('#slideshow-selector').fadeOut();
            }

            //if ($('body').hasClass('menu-open') && $target.closest('.main-menu-element').length == 0) {
            //    $('#blackboard .close a').trigger('click');
            //}
        });

        $(document).on('click', '.search-box .search-results li', function (event) {
            event.preventDefault();
            if (!$(this).closest('.search-box').hasClass('trips')) {
                $(this).closest('.search-box').find('input').val('');
            }
            var callback = eval($(this).closest('.search-box').find('input').data('callback'));
            var anchor = $(this).find('a');
            eval(callback($(anchor)));
        });

        // scroll the search results list on mousewheel scroll
        $(document).on('mousewheel', '.search-results', function (event, delta) {
            event.stopPropagation();
            event.preventDefault();
            if (delta > 0) {
                atr.menu.scrollSearchUp(10, $(this));
                atr.menu.scrollSearchStop($(this));
            } else if (delta < 0) {
                atr.menu.scrollSearchDown(10, $(this));
                atr.menu.scrollSearchStop($(this));
            }
        });
    };

    var initPageContentHandler = function () {
        $('#page-content').on('click', '.page-facilities .data-count a', function (event) {
            // find the content menu link and click it
            if ($(this).data('contentpage') !== undefined) {
                event.preventDefault();
                openContentPage($(this).data('contentpage'));
            }
        }).on('click', '#content-text header h2 a', function(event) {
            var $this = $(this);

            // guidebook page menu handler
            var sectionTitle = $(this).closest('header.menu').attr('data-section-title');

            if ($this.parent().parent().hasClass('owner')) {
                // popup the owner website
                event.preventDefault();
                ownerLink($this.attr('href'));
            } else {
                event.preventDefault();
                if (!$this.closest('header').hasClass('opened')) {
                    // open the selected quidebook page

                    var menu = $this;
                    atr.common.loadGuidebook($this.attr('href'), sectionTitle, undefined,
                        function() {
                            if (menu.data('pageurl') == 'images') {
                                atr.media.slideshow.highlightThumbnail();
                            }

                            try {
                                atr.editor.hideContentEditButtons();
                                atr.editor.showContentEditButtons();
                            } catch (ex) {
                            }

                            //// The height of the content body will have changed, so we need to rebind the scroll fix event handler
                            //$('.scroll-then-fix').each(function() {
                            //    var $this = $(this);
                            //    $this.unbind().atrScrollFix();
                            //});
                        }
                    );
                    //} else {
                    //    $('html, body').animate({ scrollTop: $this.offset().top - ($('#page-banner').outerHeight(true) * 2) + 50 }, 'medium');
                }
                $(this).blur();
            }
        }).on('click', '#content-text .article-close', function (event) {
            var currTop = 0;
            // calculate the position of the clicked-on article relative to the top of the browser window
            var closeOffset = (parseFloat($(this).parent().outerHeight(true)) - parseFloat($(this).position().top));
            currTop = $(this).offset().top - $(window).scrollTop();
            if (currTop != 0) {
                var scrollTo = $(this).parent().offset().top - (currTop + closeOffset);
            }
            $(this).parent().remove();

            // scroll the page so that the new article is in the same vertical position within the browser as it was pre-click
            // so that it doesn't look as if the page has moved
            if ($(this).hasClass('bottom') && scrollTo != 0) {
                // scroll the page to the selected article 
                $(document).scrollTop(scrollTo);
            }
        });

        $('#content-text').on('click', '.guide a', function (event) {
            //if (history && history.pushState) {
            var url;
            if ($(this).attr('href').indexOf('/find/') > 0) {
                url = $(this).attr('href').replace(/(.*)\/guide\/(.*)/, '$1');
                if (url == $('#content-text article.opened .JS-LocationUrl').html()) {
                    // this link is for the current location page
                    var name = $(this).attr('href').replace(/(.*)\/guide\/(.*)\/find\/(.*)/, '$3').replace(/\/$/, '');
                    if (name != '' && $('#content-text article.opened a[name=' + name + ']').length) {
                        // this link is in the current guidebook content
                        var goto = $('#content-text article.opened a[name=' + name + ']').offset().top;
                        ////_writeToConsole(goto);
                        if (!isNaN(goto)) {
                            event.preventDefault();
                            $('html, body').animate({ scrollTop: goto - 110 }, 'medium');

                            //history.pushState({ url: window.location.href }, document.title, $(this).attr('href'));
                        }
                    }
                }
            } else if ($(this).data('inline')) {
                // dynamically load content into the current guidebook page for links containing data-inline="true"
                event.preventDefault();
                atr.common.loadGuidebook($(this).attr('href'), $(this).html(), $(this));
            }
            //}
        });
    };
    
    var openMainMenu = function () {
        //_writeToConsole('openMainMenu starting');

        // Store the state of the media player so we can resume when the interim pages are closed
        var playMode = atr.localStorage.mediaStopped;

        if (atr.media.video.carouselIsPlaying() || atr.media.video.videoIsPlaying()) {
            playMode = atr.localStorage.mediaPlaying;
        }

        atr.localStorage.setInterimMediaStatus(atr.localStorage.getMultimediaMode(), playMode);

        // Pause any playing video or carousel
        //atr.media.video.pauseVideo();
        //atr.media.video.pauseCarousel();

        atr.interim.displayMainMenu();
    };

    //-----------------------------------------------------------------------------------------------------
    // Set up the scroll down arrow to auto scroll 2/3 of the page height
    //-----------------------------------------------------------------------------------------------------
    var initScrollDownAction = function() {

        $('.scroll-down-action').on('click', function () {
            var $window = $(window);

            var h = $window.height();
            var y = $window.scrollTop();

            if (atr.common.userIsOnTablet() == true && $(this).closest('page-facilities')) {
                y = $('#content-wrapper').offset().top - 36;
                h = 0;
            }

            $('html, body').animate({ scrollTop: y + (h * 0.75) }, 600);
        });
    }

    
    var handleAjaxError = function (err) {
        if (err.responseText != '') {
            alert(err.responseText);
        }
        ////_writeToConsole('Ajax error: ' + err.responseText);
    };

    var dateToYYYYMMDD = function (date) {
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth() + 1).toString();
        var dd = date.getDate().toString();
        //_writeToConsole('DATETOYYYYMMDD: ' + yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]));
        return yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]);
    };

    var dateToDDMMYYYY = function (date) {
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth() + 1).toString();
        var dd = date.getDate().toString();
        //_writeToConsole('dateToDDMMYYYY: ' + yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]));
        return (dd[1] ? dd : "0" + dd[0]) + "/" + (mm[1] ? mm : "0" + mm[0]) + "/" + yyyy;
    };

    var stringDDMMYYYYToYYYYMMDD = function (date) {
        // input date must be in dd/mm/yyyy format
        var datepart = date.split('/');
        return datepart[2] + "/" + datepart[1] + "/" + datepart[0];
    };

    var disableScrolling = function() {
        $('body').addClass('stop-scrolling');
        $('body').bind('touchmove', function (e) { e.preventDefault(); });
    };

    var enableScrolling = function () {
        $('body').removeClass('stop-scrolling');
        $('body').unbind('touchmove');
    };

    var rescaleGuideBookImages = function() {

        var images = $('article.opened img');
        //_writeToConsole('rescaleGuideBookImages: ' + images.length + ' images found');

        images.each(function () {

            var maxWidth = $(this).closest('div.guide').width();
            var maxHeight = 2000;

            rescaleImage($(this), maxWidth, maxHeight);
            //rescaleImageToFillWidth($(this), maxWidth);
        });
    };

    var rescaleImage = function(jqImg, maxWidth, maxHeight) {

        //_writeToConsole('rescaleImage: maxWidth = ' + maxWidth + '; maxHeight = ' + maxHeight);

        //var maxWidth = 100; // Max width for the image
        //var maxHeight = 100;    // Max height for the image
        var ratio = 0;  // Used for aspect ratio
        var width = jqImg.width();    // Current image width
        var height = jqImg.height();  // Current image height

        // Check if the current width is larger than the max
        if (width > maxWidth) {
            ratio = maxWidth / width;   // get ratio for scaling image
            jqImg.css("width", maxWidth); // Set new width
            jqImg.css("height", height * ratio);  // Scale height based on ratio
            height = height * ratio;    // Reset height to match scaled image
            width = width * ratio;    // Reset width to match scaled image
        }

        // Check if current height is larger than max
        if (height > maxHeight) {
            ratio = maxHeight / height; // get ratio for scaling image
            jqImg.css("height", maxHeight);   // Set new height
            jqImg.css("width", width * ratio);    // Scale width based on ratio
            width = width * ratio;    // Reset width to match scaled image
            height = height * ratio;    // Reset height to match scaled image
        }

        jqImg.width(width);
        jqImg.height(height);
        
    };

    var rescaleImageToFillWidth = function (jqImg, targetWidth) {

        //_writeToConsole('rescaleImage: targetWidth = ' + targetWidth);

        //var maxWidth = 100; // Max width for the image
        //var maxHeight = 100;    // Max height for the image
        var ratio = 0;  // Used for aspect ratio
        var width = jqImg.width();    // Current image width
        var height = jqImg.height();  // Current image height

        ratio = targetWidth / width;   // get ratio for scaling image
        jqImg.css("width", targetWidth); // Set new width
        jqImg.css("height", height * ratio);  // Scale height based on ratio
        height = height * ratio;    // Reset height to match scaled image
        width = width * ratio;    // Reset width to match scaled image
        
        jqImg.width(width);
        jqImg.height(height);

    };

    // The link with the text 'detailed prices and availability' and the surrounding text is removed on mobiles
    var removePricesLinkFromGuidebook = function() {
        //_writeToConsole('removePricesLinkFromGuidebook: started');

        var links = $('article.opened div.guide a').filter(function (index) { return $(this).text() === 'detailed prices and availability'; });

        if (links.length > 0) {

            links.remove();

            // Now remove the left over text
            var stringToRemove = 'Check the <b></b> for this lodge ...<br>';
            var guideHtml = $('article.opened div.guide').html();
            //_writeToConsole(guideHtml);

            if (guideHtml.indexOf('') != -1) {
                var tmp = guideHtml.split(stringToRemove);
                $('article.opened div.guide').html(tmp[0] + tmp[1]);
            }
        }

        var links2 = $('article.opened div.guide a[data-inline="true"]:contains("Prices")');

        if (links2.length > 0) {

            links2.remove();

            // Now replace the :: that will be left over with a single :
            guideHtml = $('article.opened div.guide').html();
            //_writeToConsole(guideHtml);

            if (guideHtml.indexOf('') != -1) {
                guideHtml = guideHtml.replace(':  :', ':');
                $('article.opened div.guide').html(guideHtml);
            }
        }
    };

    var tryParseInt = function(str, defaultValue) {
        var retVal = defaultValue;
        if (str != null) {
            if(str.length > 0) {
                if (!isNaN(str)) {
                    retVal = parseInt(str);
                }
            }
        }
        return retVal;
    };

    // Replaces all instances of 'find' in 'str' with 'replace'
    var replaceAll = function(str, find, replace) {
        return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
    };

    // Regular expressions contain special characters. This function escapes those characters for use in a RegExp function
    var escapeRegExp = function (str) {
        return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
    }


    var _popularPlacesLocationSourceCookieName = 'ATRLocationSourceWasPopularPlaces';
    var _setPopularPlacesWasLocationSource = function () {

        // Store the search model in a cookie - cookie lasts for 1 hour (arbitrary atm)
        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000;
        now.setTime(time);

        $.cookie(_popularPlacesLocationSourceCookieName, 'PopularPlacesSet', { expires: now, path: '/' });

    };

    var _setQueryStringParameter = function (url, key, value) {

        var uri = new URI(url);
        uri.setSearch(key, value);

        return uri;
    }

    return {
        writeToConsole: _writeToConsole,
        getQueryStringParameterByName: _getQueryStringParameterByName,
        setQueryStringParameter: _setQueryStringParameter,

        initUserAgentDetails: initUserAgentDetails,

        userIsOnMobile: userIsOnMobile,
        userIsOnTablet: userIsOnTablet,
        userIsOnIPad: userIsOnIPad,
        userIsOnDesktop: userIsOnDesktop,

        isPortrait: isPortrait,

        handleAjaxError: handleAjaxError,
        openMainMenu: openMainMenu,

        removeElements: removeElements,
        resetRemovableElements: resetRemovableElements,
        toggleRemovableElements: toggleRemovableElements,

        initThrobber: initThrobber,
        startThrobber: startThrobber,
        stopThrobber: stopThrobber,

        initCustomDropdowns: initCustomDropdowns,

        resizeBackground: resizeBackground,
        //fetchLoadableElements: fetchLoadableElements,
        loadContentIntoPanel: loadContentIntoPanel,

        initScrollDirection: initScrollDirection,
        getScrollDirection: getScrollDirection,

        loadGuidebook: loadGuidebook,
        initPageContentHandler: initPageContentHandler,
        
        initSearchHandler: initSearchHandler,
        popup: popup,

        initScrollDownAction: initScrollDownAction,
        dateToYYYYMMDD: dateToYYYYMMDD,
        dateToDDMMYYYY: dateToDDMMYYYY,
        stringDDMMYYYYToYYYYMMDD: stringDDMMYYYYToYYYYMMDD,
        searchBoxHasFocus: searchBoxHasFocus,

        disableScrolling: disableScrolling,
        enableScrolling: enableScrolling,
        //dragging: _dragging

        rescaleImage: rescaleImage,
        rescaleGuideBookImages: rescaleGuideBookImages,

        removePricesLinkFromGuidebook: removePricesLinkFromGuidebook,

        tryParseInt: tryParseInt,
        replaceAll: replaceAll,

        setPopularPlacesWasLocationSource: _setPopularPlacesWasLocationSource
}

})();

atr.common.initUserAgentDetails();

$(function () {
    atr.common.initScrollDirection();
    atr.common.initThrobber();
    atr.common.initCustomDropdowns();
    atr.common.initScrollDownAction();

    var cachedWidth = $(window).width();
    $(window).resize(function () {
        if (atr.touch != undefined) {

            var newWidth = $(window).width();
            if (newWidth !== cachedWidth) {
                atr.touch.orientationChange();
                cachedWidth = newWidth;
            }
        }
    });

    if (atr.touch != undefined)
        atr.touch.orientationChange();

    atr.common.initPageContentHandler();
    atr.common.initSearchHandler();

    if (atr.common.userIsOnDesktop() === true) {
        $(window).on('resize', function() {
             atr.common.resizeBackground();
        });
    } else {
        $(window).on('orientationchange', function () {
            atr.common.resizeBackground();
        });
    }

    // The code below is an attempt to prevent mobile browsers from scrolling the page back to the top when the search box gets the focus.
    // Doesn't seem to be working though :(
    var currentScrollPosition = 0;
    $(document).scroll(function() {
        currentScrollPosition = $(this).scrollTop();
    });

    $('#search-box input').on('focus', function () {
        $('html, body').animate({ scrolltop: currentScrollPosition}, 100);
    });

    $('#wishlist-icon').on('click', function () {
        if (atr.common.userIsOnMobile() == true) return;
        window.location.href = $(this).attr('data-url');
    });

    $(document).on('scroll touchmove mousewheel', function (e) {
        if ($('body').hasClass('stop-scrolling')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    //atr.common.dragging = false;

    //$('body').on('touchmove', function () {
    //    //_writeToConsole('dragging...');
    //    atr.common.dragging = true;
    //});

    //$('body').on('touchend', function () {
    //    //_writeToConsole('dragging ended');
    //    if (atr.common.dragging) {
    //        //atr.common.dragging = false;
    //        return;
    //    }
    //});


    // http://stackoverflow.com/questions/8291517/disable-hover-effects-on-mobile-browsers?noredirect=1&lq=1
    $(document)
        .on('touchstart',
            function(e) {
                //_writeToConsole('dragging started');
                //atr.common.dragging = false;
                $(e.target).addClass('touched');
                //_writeToConsole('element has been touched');
            });

    $(document)
        .on('click',
            function(e) {
                if ($(e.target).hasClass('touched')) {
                    $(e.target).removeClass('touched');
                    //_writeToConsole('element touch end');
                }
            });
});


function openDropdown(elem) {
    if (document.createEvent) {
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        elem[0].dispatchEvent(e);
    } else if (element.fireEvent) {
        elem[0].fireEvent("onmousedown");
    }
}