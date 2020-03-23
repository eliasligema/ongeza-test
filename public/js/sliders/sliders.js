var atr = atr || {};

atr.components = atr.components || {};

atr.components.sliders = (function() {

    var _sliderNavIsRunning = false;

    var _storageObject = sessionStorage;
    var _pageSliderPositionsStorageKey = 'atr_page_slider_positions';

    var _getPageSliderPositions = function () {

        var storageValue = _storageObject.getItem(_pageSliderPositionsStorageKey);
        if (storageValue) {
            if (storageValue.length > 0) {
                return JSON.parse(storageValue);
            }
        }

        return [];
    };

    var _storeSliderPositionsArray = function (pageSliderPositions) {
        _storageObject.setItem(_pageSliderPositionsStorageKey, JSON.stringify(pageSliderPositions));
    };

    var _setLodgeSliderNavArrow = function(sliderNav, sampleLodgeSliderItem) {

        var $this = $(sliderNav);

        // The arrow is positioned vertically in the middle of the image, but some images may have text above them
        // So we need to calculate the height of the image + the text above.
        var bestLodgesItemArea = $(sampleLodgeSliderItem).find('.best-lodges-item-area').first();
        var bestLodgesItemDetails = $(sampleLodgeSliderItem).find('.best-lodges-item-details').first();
        var imageWrapper = $(sampleLodgeSliderItem).find('.image-wrapper').first();

        var b1 = bestLodgesItemArea == null ? 0 : $(bestLodgesItemArea).outerHeight(true);
        var b2 = bestLodgesItemDetails == null ? 0 : $(bestLodgesItemDetails).outerHeight(false);
        var w = imageWrapper == null ? 0 : $(imageWrapper).outerHeight(true);
        var h = b1 + b2 + w;
        $this.height(h);

        // Now position the arrow image within the clickable area
        var span = $this.find('a.atr-clickable-area span');
        if (span.length > 0) {
            var spanH = $(span[0]).outerHeight(true);

            //console.log('b1 = ' + b1 + '; b2 = ' + b2 + '; w = ' + w + '; spanH = ' + spanH);
            var t = b1 + (w / 2) - (spanH / 2);
            $(span[0]).css('top', t);
        }
    }

    var _setTripCardSliderNavArrow = function(sliderNav, sampleTripCard) {

        var $this = $(sliderNav);

        // The arrow is positioned vertically in the middle of the map.
        // If the trip has a description and/or a header above it, we need to calculate their heights + the height of the map
        var tripDescription = $(sampleTripCard).find('.trip-card-description').first();
        var tripHeader = $(sampleTripCard).find('.trip-card-header').first();
        var mapWrapper = $(sampleTripCard).find('.trip-card-map-wrapper').first();
        var t1 = tripDescription == null ? 0 : $(tripDescription).outerHeight(true);
        var t2 = tripHeader == null ? 0 : $(tripHeader).outerHeight(true);
        var h2 = mapWrapper == null ? 0 : $(mapWrapper).outerHeight(true);
        $this.css('top', (t1 + t2) + 'px').height(h2);

    }

    /***************************************************************
        Positions the slider nav arrows relative to the items beings displayed
    ***************************************************************/
    var _setSliderNavPositions = function () {

        // The position of the arrow is different depending on the type of elements in the slider.
        // We need to calculate the height of the panel in which the arrow sits.
        $('.atr-slider-nav').each(function () {
            var $this = $(this);

            // Get the display port containing this nav element - there could be several sliders on the page
            var displayPort = $this.closest('.atr-slider-display-port');

            // Get the first item in the slider
            var sliderItem = $(displayPort).find('.atr-slider-items-collection ul li').first();

            // Do we have a Lodge Slider Item
            var lodgeSliderItem = $(sliderItem).find('.lodge-slider-item').first();
            if (lodgeSliderItem.length > 0) {

                _setLodgeSliderNavArrow($(this), lodgeSliderItem);

            } else {
                // Do we have a trip card?
                var tripCard = $(sliderItem).find('.trip-card').first();
                if (tripCard.length > 0) {
                    _setTripCardSliderNavArrow($(this), tripCard);
                } else {

                    // Otherwise just set the height of the arrow panel to the height of the slider item
                    $this.height($(sliderItem).outerHeight(true));
                }
            }
        });
    };

    var _setSliderPosition = function (sliderId, left) {

        var url = document.location.pathname;

        // Retrieve the main array from storage. This array holds the positions of all the sliders on the last 10 visited pages
        var a = _getPageSliderPositions();

        // Look for the url in the array
        var page = _.find(a, function (p) { return p.url === url; });
        if (page == null) {
            // If the page hasn't been stored yet, create a new page object for the url and add the slider item to the page's sliders collection
            page = {
                url: url,
                sliders: [{ sliderId: sliderId, left: left }]
            };

            // Add the page to the main array
            a.push(page);

        } else {
            // Find the slider id in the page's slider collection
            var s = _.find(page.sliders, function (slider) { return slider.sliderId === sliderId; });
            if (s == null || s === 'undefined') {
                // if the slider hasn't been found, create a new slider object and add it to the page's sliders collection
                page.sliders.push({ sliderId: sliderId, left: left });
            } else {
                // Update the left position of the slider found
                s.left = left;
            }
        }

        // store the slider positions array
        _storeSliderPositionsArray(a);
    };

    var _loadLazyImagesInView = function() {

        $('.atr-slider')
            .each(function(index, slider) {

                var displayPortContainer = $(this).find('.atr-slider-items-collection').first();
                if (typeof displayPortContainer === 'undefined') return;

                var containerWidth = $(displayPortContainer).width();
                
                var sliderItems = $(displayPortContainer).find('a.slider-item');
                $(sliderItems)
                    .each(function (index, item) {

                        var lazyImage = $(this).find('img.atr-lazy-image').first();
                        if (typeof lazyImage === 'undefined') return;

                        var itemLeftPosition = $(item).offsetRelative('atr-slider-items-collection').left;

                        var minLeft = containerWidth * -1;
                        var maxLeft = containerWidth * 2;
                        if (itemLeftPosition >= minLeft && itemLeftPosition < maxLeft) {
                            $(lazyImage).attr('src', $(lazyImage).attr('data-original'));
                        }
                    });


            });

    };

    var _initSliderComponents = function() {

        //setSliderNavPositions();
        _loadLazyImagesInView();

        /***************************************************************
            Attches click handlers to the navigation links on all sliders
        ***************************************************************/
        $('.atr-slider-nav a').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            if (_sliderNavIsRunning === true) return;
            _sliderNavIsRunning = true;

            var root = $this.closest('.jq-slider-wrapper');

            var direction = $this.closest('.atr-slider-nav');
            var ul = $(root).find('.atr-slider-items-collection ul');

            //var li = $(root).find('.atr-slider-items-collection ul li').first();
            //var a = li.find('a');

            //console.log('.atr-slider-items-collection.outerWidth(true) = ' + $('.atr-slider-items-collection').outerWidth(true));
            //console.log('li.outerWidth(true) = ' + li.outerWidth(true));
            //console.log('a.outerWidth() = ' + a.outerWidth());

            var viewportWidth = $(root).find('.atr-slider-items-collection').outerWidth(true); // + (li.outerWidth(true) - a.outerWidth());

            var l;
            if (direction.hasClass('nav-next-set')) {
                l = parseInt(ul.css('left')) - viewportWidth;
                //l = Math.floor(ul.position().left - viewportWidth); // position().left is inaccurate in FF/IE
            } else {
                l = parseInt(ul.css('left')) + viewportWidth;
                //l = Math.floor(ul.position().left + viewportWidth); // position().left is inaccurate in FF/IE
            }

            if (l > 0) l = 0;

            //console.log('l = ' + l);

            if (direction.css('visibility') !== 'hidden' && l <= 0) {
                    ul.css('left', l + 'px');
            }

            var lastItem = ul.find('li').last();
            if (l * -1 > lastItem.position().left - viewportWidth) {
                // Hide the 'next' arrow
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'hidden');
            } else {
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'visible');
            }

            // Store the position so we can return to the same position
            //atr.localStorage.setBestLodgesWidgetPositionForUrl(window.location.href, l);

            if (l < 0) {
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'visible');
            } else {
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'hidden');
            }

            // Give the animation time to complete
            setTimeout(function () {

                _loadLazyImagesInView();

                _sliderNavIsRunning = false;
                //console.log('slideNavIsRunning set to false');
            }, 1000);

            // Store the new position of this slider
            _setSliderPosition(root.attr('id'), l);
        });

        // initially, hide the 'previous' arrow
        $('.atr-slider-display-port .nav-previous-set').css('visibility', 'hidden');


        /***************************************************************
            When the window is resized, we need to re-position the slider nav arrows
        ***************************************************************/

        var cachedWindowWidth = $(window).width();
        $(window).resize(function () {

            var newWidth = $(window).width();
            if (newWidth !== cachedWindowWidth) {
                $('.atr-slider-items-collection ul').css('left', '0');
                $('.atr-slider-display-port .nav-next-set').css('visibility', 'visible');
                $('.atr-slider-display-port .nav-previous-set').css('visibility', 'hidden');
            }

            // Set the height of the nav element containers
            _setSliderNavPositions();
        });
    };


    var _initSliderComponent = function (componentId) {

        //setSliderNavPositions();
        _loadLazyImagesInView();

        /***************************************************************
            Attches click handlers to the navigation links on all sliders
        ***************************************************************/
        $(componentId + ' .atr-slider-nav a').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            if (_sliderNavIsRunning === true) return;
            _sliderNavIsRunning = true;

            var root = $this.closest('.jq-slider-wrapper');

            var direction = $this.closest('.atr-slider-nav');
            var ul = $(root).find('.atr-slider-items-collection ul');

            //var li = $(root).find('.atr-slider-items-collection ul li').first();
            //var a = li.find('a');

            //console.log('.atr-slider-items-collection.outerWidth(true) = ' + $('.atr-slider-items-collection').outerWidth(true));
            //console.log('li.outerWidth(true) = ' + li.outerWidth(true));
            //console.log('a.outerWidth() = ' + a.outerWidth());

            var viewportWidth = $(root).find('.atr-slider-items-collection').outerWidth(true); // + (li.outerWidth(true) - a.outerWidth());

            var l;
            if (direction.hasClass('nav-next-set')) {
                l = parseInt(ul.css('left')) - viewportWidth;
                //l = Math.floor(ul.position().left - viewportWidth); // position().left is inaccurate in FF/IE
            } else {
                l = parseInt(ul.css('left')) + viewportWidth;
                //l = Math.floor(ul.position().left + viewportWidth); // position().left is inaccurate in FF/IE
            }

            if (l > 0) l = 0;

            //console.log('l = ' + l);

            if (direction.css('visibility') !== 'hidden' && l <= 0) {
                ul.css('left', l + 'px');
            }

            var lastItem = ul.find('li').last();
            if (l * -1 > lastItem.position().left - viewportWidth) {
                // Hide the 'next' arrow
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'hidden');
            } else {
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'visible');
            }

            // Store the position so we can return to the same position
            //atr.localStorage.setBestLodgesWidgetPositionForUrl(window.location.href, l);

            if (l < 0) {
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'visible');
            } else {
                $(root).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'hidden');
            }

            // Give the animation time to complete
            setTimeout(function () {

                _loadLazyImagesInView();

                _sliderNavIsRunning = false;
                //console.log('slideNavIsRunning set to false');
            }, 1000);

            // Store the new position of this slider
            _setSliderPosition(root.attr('id'), l);
        });

        // initially, hide the 'previous' arrow
        $(componentId + ' .atr-slider-display-port .nav-previous-set').css('visibility', 'hidden');


        /***************************************************************
            When the window is resized, we need to re-position the slider nav arrows
        ***************************************************************/

        var cachedWindowWidth = $(window).width();
        $(window).resize(function () {

            var newWidth = $(window).width();
            if (newWidth !== cachedWindowWidth) {
                $(componentId + ' .atr-slider-items-collection ul').css('left', '0');
                $(componentId + ' .atr-slider-display-port .nav-next-set').css('visibility', 'visible');
                $(componentId + ' .atr-slider-display-port .nav-previous-set').css('visibility', 'hidden');
            }

            // Set the height of the nav element containers
            _setSliderNavPositions();
        });
    };

    // This function looks in the stored slider positions array cookie for the current page and sets the positions of any slider on the page
    var _initSliderPositionsForCurrentPage = function() {

        var url = document.location.pathname;

        // Retrieve the main array from storage. This array holds the positions of all the sliders on the last 10 visited pages
        var a = _getPageSliderPositions();

        // Are there any pages matching the current url stored in the array. If not, do nothing
        var page = _.find(a, function (p) { return p.url === url; });
        if (page == null) return;

        // Go through each of the slider id's in the retrieved page and if there is an element on the page matching the id, set its left position
        _.each(page.sliders, function(element, index, list) {
            var el = $('#' + element.sliderId);
            if (!el.length) return;

            var l = element.left;

            var ul = $(el).find('.atr-slider-items-collection ul');
            ul.addClass('no-transition');
            ul.css('left', l + 'px');
            //var h = ul[0].offsetHeight;
            ul.removeClass('no-transition');

            var viewportWidth = $(el).find('.atr-slider-items-collection').outerWidth(true);
            var lastItem = ul.find('li').last();
            if (l * -1 > lastItem.position().left - viewportWidth) {
                // Hide the 'next' arrow
                $(el).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'hidden');
            } else {
                $(el).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'visible');
            }

            if (l < 0) {
                $(el).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'visible');
            } else {
                $(el).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'hidden');
            }
        });
    }

    // If we are on a Trip Collection page, the Trip Collection slider should be positioned to show the current collection
    var _setTripCollectionSliderPosition = function() {

        var url = document.location.pathname;

        // Ensure we have a trip collection slider on the page
        if ($('#trip-collections.jq-slider-wrapper').length === 0) return;

        // Get the slider
        var slider = $('#trip-collections.jq-slider-wrapper');

        // Get all the li elements in the slider
        var sliderElements = $('#trip-collections.jq-slider-wrapper .atr-slider-display-port .atr-slider-items-collection ul li');
        
        // Find an li slider item who's href matches the current url
        var indexes = $.map($(sliderElements), function(obj, index) {
            var anchors = $(obj).find('a.slider-item');
            if (anchors.length === 1) {
                var a = $(anchors).eq(0);
                var href = a.attr('href');

                if (href === url) return index;

                var pluralHref = url.replace('-safari/', '-safaris/');
                if (pluralHref === href) return index;
            }
        });

        // If we haven't found one, don't proceed
        if (indexes.length === 0) return;

        // calculate what the left position of the ul should be
        var lPos = $(sliderElements).eq(0).outerWidth(true) * (indexes[0]) * -1;

        // Find the ul and set its left position
        var ul = $(slider).find('.atr-slider-items-collection ul');
        ul.addClass('no-transition');
        ul.css('left', lPos + 'px');
        ul.removeClass('no-transition');

        _setSliderPosition('trip-collections', lPos);

        //var viewportWidth = $(slider).find('.atr-slider-items-collection').outerWidth(true);
        //var lastItem = ul.find('li').last();
        //if (lPos * -1 > lastItem.position().left - viewportWidth) {
        //    // Hide the 'next' arrow
        //    $(slider).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'hidden');
        //} else {
        //    $(slider).find('.atr-slider-display-port .atr-slider-nav.nav-next-set').css('visibility', 'visible');
        //}

        //if (lPos < 0) {
        //    $(slider).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'visible');
        //} else {
        //    $(slider).find('.atr-slider-display-port .atr-slider-nav.nav-previous-set').css('visibility', 'hidden');
        //}
    };

    var _setSliderItemCounts = function() {

        // Trip items in the slider
        if (window.innerWidth <= 1024) {
            $('.atr-slider-items-collection.trip-items-collection').removeClass('atr-slider-col-4').removeClass('atr-slider-col-3').removeClass('atr-slider-col-2').addClass('atr-slider-col-1');
        } else
            if (window.innerWidth <= 1380) {
                $('.atr-slider-items-collection.trip-items-collection').removeClass('atr-slider-col-4').removeClass('atr-slider-col-3').addClass('atr-slider-col-2').removeClass('atr-slider-col-1');
            } else {
                $('.atr-slider-items-collection.trip-items-collection').removeClass('atr-slider-col-4').addClass('atr-slider-col-3').removeClass('atr-slider-col-2').removeClass('atr-slider-col-1');
            }

        // Other items in the sliders
        if (window.innerWidth <= 1140) {
            // Below 1140px, display 2 items in the slider
            $('.atr-slider-items-collection').not('.trip-items-collection').removeClass('atr-slider-col-4').removeClass('atr-slider-col-3').addClass('atr-slider-col-2').removeClass('atr-slider-col-1');
        } else
            if (window.innerWidth <= 1380) {
                // Between 1140 and 1380, display 3 items in the slider
                $('.atr-slider-items-collection').not('.trip-items-collection').removeClass('atr-slider-col-4').addClass('atr-slider-col-3').removeClass('atr-slider-col-2').removeClass('atr-slider-col-1');
            } else {
                // Above 1280px, display 4 items in the slider
                $('.atr-slider-items-collection').not('.trip-items-collection').addClass('atr-slider-col-4').removeClass('atr-slider-col-3').removeClass('atr-slider-col-2').removeClass('atr-slider-col-1');
            }

    }

    return {
        initSliderComponents: _initSliderComponents,
        initSliderComponent: _initSliderComponent,

        initSliderPositionsForCurrentPage: _initSliderPositionsForCurrentPage,
        setSliderPosition: _setSliderPosition,
        setTripCollectionSliderPosition: _setTripCollectionSliderPosition,

        setSliderItemCounts: _setSliderItemCounts,
        setSliderNavPositions: _setSliderNavPositions
    }
})();

$(function () {
    atr.components.sliders.setTripCollectionSliderPosition();
    atr.components.sliders.initSliderComponents();
    atr.components.sliders.setSliderItemCounts();

    $(window).on('resize', function () { atr.components.sliders.setSliderItemCounts(); });
});



atr.components.common = (function() {

    var _loadLazyComponents = function() {

        $('.atr-lazy-load').not('.loaded').each(function(index, item) {

            var $this = $(this);
            var url = $this.attr('data-url');
            var startTime = Date.now();

            if (atr.debug.writeToConsoleForAtrCommonComponents === true) {
                console.log('loading component from url ' + url);
            }

            $.when(
                $.ajax({
                    url: url.toLowerCase(),
                    type: 'GET',
                    dataType: 'html',
                    statusCode: {
                        404: function () {
                            console.log('..unable to load component from url ' + url);
                        }
                    }
                })
                    .then(function (html) {
                    if (atr.debug.writeToConsoleForAtrCommonComponents === true) {
                        console.log('loading component from url (' + url + ') data returned in (ms): ' + (Date.now() - startTime));
                    }

                    $this.html(html);

                    // Add the class 'loaded' so that we don't try to load this div again.
                    $this.addClass('loaded');

                    if (atr.debug.writeToConsoleForAtrCommonComponents === true) {
                        console.log('loading component from url (' + url + ') completed time (ms): ' + (Date.now() - startTime));
                    }

                }),
                function (data) {
                    //error
                    atr.common.handleAjaxError(data);
                });
        });
    }

    return {
        loadLazyComponents: _loadLazyComponents
    }
})();