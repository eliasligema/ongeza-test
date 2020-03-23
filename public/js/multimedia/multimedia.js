var atr = atr || {};

atr.multimedia = (function () {

    var _slides = [];
    var _videos = [];

    var _currentSlideIndex = 0;
    var _bv;

    var _autoplayDisplayPeriod = 5;
    var _autoplayerIsPaused = false;

    var _displayModeVideo = 'video';
    var _displayModeImages = 'images';

    var _playModePlaying = 'playing';
    var _playModePaused = 'paused';
    
    var _multimediaDisplayModeCookieName = 'ATRMultimediaDisplayMode';
    var _multimediaPlayModeCookieName = 'ATRMultimediaPlayMode';

    var _overrideMode = false;

    var _writeToConsole = function (msg) {
        if (atr.debug.writeToConsoleForAtrMultimedia === true) console.log(msg);
    };

    var _writeErrorToConsole = function (msg) {
        if (atr.debug.writeToConsoleForAtrMultimedia === true) console.log(msg);
    };

    var _getMultimediaDisplayMode = function () {
        var c = $.cookie(_multimediaDisplayModeCookieName);
        return c == null ? '' : c;
    }

    var _setMultimediaDisplayMode = function (displayMode) {
        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000; // 1 hour
        now.setTime(time);

        $.cookie(_multimediaDisplayModeCookieName, displayMode, { expires: now, path: '/' });
    }

    var _getMultimediaPlayMode = function () {
        var c = $.cookie(_multimediaPlayModeCookieName);
        return c == null ? '' : c;
    }

    var _setMultimediaPlayMode = function (playMode) {
        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000; // 1 hour
        now.setTime(time);

        $.cookie(_multimediaPlayModeCookieName, playMode, { expires: now, path: '/' });
    }

    var _currentDisplayMode = _displayModeImages;
    var _currentPlayMode = _playModePaused;

    var _determineCurrentOverrideMode = function() {
        _overrideMode = $('.atr-multimedia-viewer').attr('data-override-initial-mode');
    }

    var _determineCurrentDisplayMode = function() {

        // The default display mode is the mode set on the viewer. This may be overridden
        var defaultDisplayMode = $('.atr-multimedia-viewer').attr('data-display-mode');

        // If the user is on mobile, the mode should default to images - manual
        if (atr.common.userIsOnMobile() === true) {
            defaultDisplayMode = _displayModeImages;
        }

        // Do we care about any user action on previous pages?
        if (_overrideMode === 'false') {
            _currentDisplayMode = defaultDisplayMode;
        } else {

            // Check to see if the multimedia mode cookie has been set. If so, use this value
            var cookieDisplayMode = _getMultimediaDisplayMode();

            // Determine what the initial display mode should actually be
            _currentDisplayMode = cookieDisplayMode === '' ? defaultDisplayMode : cookieDisplayMode;
        }

        _writeToConsole('_currentDisplayMode = ' + _currentDisplayMode);
    };

    var _determineCurrentPlayMode = function() {

        var defaultPlayMode = $('.atr-multimedia-viewer').attr('data-play-mode');;

        // Do we care about any user action on previous pages?
        if (_overrideMode === 'false') {
            _currentPlayMode = defaultPlayMode;
        } else {
            var cookiePlayMode = _getMultimediaPlayMode();

            _currentPlayMode = cookiePlayMode === '' ? defaultPlayMode : cookiePlayMode;
        }

        _writeToConsole('_currentPlayMode = ' + _currentPlayMode);
    }


    //----------------------------------------------------------------------
    // Read the image urls from any spans with the class jq-image-data
    //----------------------------------------------------------------------
    var _loadImageDataForSlideshow = function () {

        $('.jq-image-data')
            .each(function (index) {
                _slides.push({
                    'slideIndex': index + 1,
                    'url': $(this).attr('data-url'),
                    'tabletUrl': $(this).attr('data-tablet-url'),
                    'ipadUrl': $(this).attr('data-ipad-url'),
                    'smallUrl': $(this).attr('data-small-url'),
                    'caption': $(this).attr('data-caption'),
                    'albumName': $(this).attr('data-slideshow-album')
                });
            });
    };

    //----------------------------------------------------------------------
    // Read the video urls from any spans with the class jq-video-data
    //----------------------------------------------------------------------
    var _loadVideoData = function () {

        $('.jq-video-data')
            .each(function (index) {
                _videos.push($(this).attr('data-url'));
            });
    };

    var _setupVideoPlayer = function () {

        $('.atr-videos-wrapper').height(window.innerHeight);

        try {
            // init the BigVideo object
            _bv = new $.BigVideo(
            {
                container: $('.atr-video'),
                forceAutoplay: false,
                controls: false,
                doLoop: false,
                shrinkable: false,
                useFlashForFirefox: false
            });

            _bv.init();

            _bv.showPlaylist(_videos);
            _bv.getPlayer().pause();
            
            

            //-----------------------------------------------------------------------------------------------------
            // Make the slideshow visible as soon as we know the duration of the video - i.e. download has started
            //-----------------------------------------------------------------------------------------------------
            _bv.getPlayer().on('durationchange', function () {
                $('.atr-videos-wrapper').css({ 'visibility': 'visible' });
            });

            _bv.getPlayer().on('error', function (e) {
                _writeToConsole("Error in video player: ");
                _writeToConsole(e.target.innerHTML);
                //atr.media.showImage();
            });

            _bv.getPlayer().on('ended', function (e) {
                //videoHasEnded = true;
                //atr.media.switchToCarousel(true, true);
            });

            _bv.getPlayer().on('play', function() {
                $('#big-video-wrap').attr('width', '100%');
                $('#big-video-wrap').attr('height', '100%');
            });

        } catch (ex) {
            _writeToConsole('Error in atr.media.video.setupVideoPlayer - ' + ex.message);
            //atr.media.showImage();
        }
    };


    var _pauseVideo = function () {

        if (!_bv) return;

        if (!_bv.getPlayer().paused()) {
            _bv.getPlayer().pause();
        }
    }

    var _playVideo = function () {

        if (!_bv) {
            _setupVideoPlayer();
        };

        if (_bv.getPlayer().paused()) {
            _bv.getPlayer().play();
        }
    }

    //----------------------------------------------------------------------
    // pick a number between 0 and the number of items in the _slides array
    //----------------------------------------------------------------------
    var _selectInitialImageIndex = function () {
        return Math.floor(Math.random() * _slides.length);
    };

    
    //----------------------------------------------------------------------
    // rootUrl is expected to be a filepath to an image with resolution -1475
    // this method looks at the user's platform and returns a more appropriate size
    //----------------------------------------------------------------------
    var _buildImageUrl = function(rootUrl) {

        if (atr.common.userIsOnDesktop() === true)
            return rootUrl;

        var suffix = '';
        if (atr.common.userIsOnIPad() === true) {
            suffix = '-1024';
        } else if (atr.common.userIsOnTablet() === true) {
            suffix = '-1280';
        } else if (atr.common.userIsOnMobile() === true) {
            suffix = '-768';
        }

        return rootUrl.replace('-1475', suffix);
    };
    
    //----------------------------------------------------------------------
    // creates a new atr-image div and appends it to the atr-images-wrapper div
    //----------------------------------------------------------------------
    var _loadSlideIntoWrapper = function (slideIndexToLoad, makeActive, callbackFn) {

        if (slideIndexToLoad === undefined)
            return;

        if (_slides.length <= slideIndexToLoad)
            return;

        var slide = _slides[slideIndexToLoad];

        var slideToLoad = $('.atr-images-wrapper .atr-image[data-slide-index="' + slideIndexToLoad + '"]');
        if (slideToLoad.length === 0) {

            var urlToLoad = _buildImageUrl(slide.url);
            // console.log(urlToLoad)
            // Load the current slide image into a temporary img tag so we download it to the browser
            var _image = $("<img/>")
                .attr("id", "slide-" + slideIndexToLoad)
                .attr("src", urlToLoad)
                .attr("data-slide-index", slideIndexToLoad)
                .on("error", function (e) {
                    //slideIndexToLoad = e.target.attribute  .attr("data-slide-index", slideIndexToLoad)
                    console.log(e);
                    slideIndexToLoad = slideIndexToLoad + 1;
                    _writeErrorToConsole("Error loading image " + slideIndexToLoad + " from " + urlToLoad);
                    _loadSlideIntoWrapper(slideIndexToLoad , makeActive, callbackFn);
                });

                // console.log(_image.get(0))

                _image.load(function () {
                    $(this).remove(); // to prevent memory leaks
                    console.log('Start load function');

                    // Create a new div
                    $("<div/>",
                        {
                            'class': makeActive === true ? 'atr-image' : 'atr-image inactive',
                            'data-slide-index': slideIndexToLoad,
                            'data-caption': slide.caption,
                            'style': "background-image:url(" + urlToLoad + ")"
                        })
                        .appendTo('.atr-images-wrapper');

                    if (makeActive === true) {
                        // Set the caption
                        $(".atr-image-caption-wrapper > span").html(slide.caption);

                        // Set the counter
                        var counterText = (slideIndexToLoad + 1) + '/' + _slides.length;
                        $('.atr-image-counter-wrapper > span.jq-image-counter').text(counterText);

                        // Set the album chapter
                        $('.atr-image-counter-wrapper > span.jq-image-chapter').text(slide.albumName);
                    }
                    //console.log('End load function');

                    if (callbackFn) {
                        callbackFn.call();
                    }
                });

        } else {
            if (callbackFn) {
                callbackFn.call();
            }
        }
    };


    var _preloadNextImages = function (callbackFn) {

        // Load the next slide
        var nextSlideIndex = _currentSlideIndex + 1 === _slides.length ? 0 : _currentSlideIndex + 1;

        _loadSlideIntoWrapper(nextSlideIndex, false, function () {

            // Load the previous slide after the next slide has been loaded
            var prevSlideIndex = _currentSlideIndex === 0 ? _slides.length - 1 : _currentSlideIndex - 1;

            _loadSlideIntoWrapper(prevSlideIndex, false, function () {

                if (callbackFn) {
                    callbackFn.call();
                }

            });
                

        });
        
    }


    var _switchToImages = function () {

        _writeToConsole('switching to Images');

        // pause the video
        _pauseVideo();

        // Display the Image wrapper
        $('.atr-images-wrapper').removeClass('atr-hidden');

        // Hide the Video wrapper
        $('.atr-videos-wrapper').addClass('atr-hidden');

        // Display the image slideshow nav buttons
        if (atr.common.userIsOnDesktop() === true) {
            $('.atr-image-nav-controls-wrapper').show();
        }

        // Hide the image nav buttons
        $('.atr-img-nav-button').css('visibility', 'visible');

    };


    var _switchToVideo = function () {

        _writeToConsole('switching to Video');

        // pause the carousel
        _autoplayerIsPaused = true;

        // Hide the Image wrapper
        $('.atr-images-wrapper').addClass('atr-hidden');

        // Display the Video wrapper
        $('.atr-videos-wrapper').removeClass('atr-hidden');

        // Start the video
        _playVideo();

        // Set the media control button to pause
        $('.atr-multimedia-controls .atr-multimedia-control').removeClass('atr-play').addClass('atr-pause');

        // Hide the image slideshow nav buttons
        $('.atr-image-nav-controls-wrapper').hide();

        // Hide the image nav buttons
        $('.atr-img-nav-button').css('visibility', 'hidden');

    };

    //----------------------------------------------------------------------
    // Configures the viewer to display a single image with navigation buttons
    //      The slideshow album selector is also displayed
    //----------------------------------------------------------------------
    var _startManualSlideshow = function () {

        _switchToImages();

        _currentPlayMode = _playModePaused;

        if (_videos.length === 0) {
            // Hide the Multimedia switch controls
            $('.atr-multimedia-controls').addClass('atr-hidden');
        }

        // Display the image nav buttons
        $('.atr-img-nav-button').css('visibility', 'visible');

    }


    var _startVideo = function () {

        _writeToConsole('_startVideo called');
        _switchToVideo();

        // Display the Multimedia switch controls
        $('.atr-multimedia-controls').addClass('atr-hidden');

        // Hide the image nav buttons
        $('.atr-img-nav-button').css('visibility', 'hidden');

    }

    //----------------------------------------------------------------------
    // Starts the slideshow playing automatically
    //      autoplayDisplayPeriod = number of seconds an image is displayed
    //----------------------------------------------------------------------
    var autoplayerIntervalId;

    var _startAutoplaySlideshow = function () {
        
        if (_videos.length === 0) {
            // Hide the Multimedia switch controls
            $('.atr-multimedia-controls').addClass('atr-hidden');
        }

        // Hide the image nav buttons
        $('.atr-img-nav-button').css('visibility', 'hidden');

        // Clear the timer for the auto player
        clearInterval(autoplayerIntervalId);

        // start a new timer
        autoplayerIntervalId = setInterval(function () {

            if (_autoplayerIsPaused === true) return;

            // Simulate clicking the Next button
            var btn = $('.atr-multimedia-viewer .atr-img-nav-button.atr-next');
            $(btn).trigger('click');

        },
        _autoplayDisplayPeriod * 1000);
    };

    //----------------------------------------------------------------------
    // sets up the event handlers for the image slideshow navigation buttons
    //----------------------------------------------------------------------
    var _atrImgNavButtonClickHandlerRunning = false;
    var _attachEventHandlers = function () {

        if (_slides.length === 1) {
            $('.atr-img-nav-button').hide();
            return;
        }

        $('.atr-img-nav-button')
            .on('click',
                function (e) {
                    e.preventDefault();
                    var $this = $(this);

                    if (_atrImgNavButtonClickHandlerRunning === true) return;

                    _atrImgNavButtonClickHandlerRunning = true;

                    // Work out which slide to display next
                    var slideIndexToDisplay = _currentSlideIndex + parseInt($(this).attr('data-direction'));
                    if (slideIndexToDisplay < 0) slideIndexToDisplay = _slides.length - 1;
                    if (slideIndexToDisplay > _slides.length - 1) slideIndexToDisplay = 0;

                    // load the image into the browser
                    _loadSlideIntoWrapper(slideIndexToDisplay, false);
                    

                    // Hide the image currently being displayed
                    $('.atr-image[data-slide-index="' + _currentSlideIndex + '"]').addClass('inactive');

                    // Display the next slide
                    $('.atr-image[data-slide-index="' + slideIndexToDisplay + '"]').removeClass('inactive');

                    _currentSlideIndex = slideIndexToDisplay;

                    // Load the caption
                    $('.atr-image-caption-wrapper > span').html(_slides[_currentSlideIndex].caption);

                    // Set the counter
                    var counterText = (_currentSlideIndex + 1) + '/' + _slides.length;
                    $('.atr-image-counter-wrapper > span.jq-image-counter').text(counterText);

                    // Set the album chapter
                    $('.atr-image-counter-wrapper > span.jq-image-chapter').text(_slides[_currentSlideIndex].albumName);

                    // update the recent image in the recent locations list
                    var currentLookupId = $this.closest('.atr-multimedia-viewer').attr('data-lookup-id');
                    var currentLookupType = $this.closest('.atr-multimedia-viewer').attr('data-lookup-type');

                    atr.localStorage.setRecentLocationImage(currentLookupId, currentLookupType, _currentSlideIndex);

                    _preloadNextImages(function () {

                        // Only rest the flag once the images have been loaded
                        _atrImgNavButtonClickHandlerRunning = false;
                    });
                });

        $('.atr-img-nav-button, .atr-image-chapter-selector-wrapper')
            .on('mouseenter',
                function(e) {
                    $('.main-viewport .main-title-section').addClass('atr-opaque');
                    $('.atr-image-counter-wrapper').removeClass('atr-opaque');
                    $('.atr-image-caption-wrapper').removeClass('atr-opaque');
                    $('.atr-image-chapter-selector-wrapper').removeClass('atr-opaque');
                });

        $('.atr-img-nav-button, .atr-image-chapter-selector-wrapper')
            .on('mouseleave',
                function (e) {
                    $('.main-viewport .main-title-section').removeClass('atr-opaque');
                    $('.atr-image-counter-wrapper').addClass('atr-opaque');
                    $('.atr-image-caption-wrapper').addClass('atr-opaque');
                    $('.atr-image-chapter-selector-wrapper').addClass('atr-opaque');
                });

        //-----------------------------------------------------------------------------
        // The .atr-multimedia-control switches between play and pause
        //-----------------------------------------------------------------------------
        $('.atr-multimedia-controls .atr-multimedia-control')
            .on('click',
                function (e, sourceAction) {
                    e.preventDefault();
                    var $this = $(this);

                    _writeToConsole('_currentDisplayMode = ' + _currentDisplayMode);

                    switch (_currentDisplayMode) {
                        case _displayModeImages:

                            _writeToConsole('We are displaying images');

                            // If we are in scrolling mode, we only consider acting if the data-play-mode is true
                            if (sourceAction === 'scrolling' && $('.atr-multimedia-viewer').attr('data-play-mode') === 'paused') {
                                e.stopPropagation();
                                return;
                            }

                            if ($this.hasClass('atr-play')) {
                                // We are paused, so we need to un-pause the carousel
                                _writeToConsole('We are paused, so we need to un-pause the carousel');
                                _currentPlayMode = _playModePlaying;
                                _autoplayerIsPaused = false;

                                // First, move to the next slide so the user sees something happening
                                $('.atr-multimedia-viewer .atr-img-nav-button.atr-next').trigger('click');
                                
                                // start the autoplayer
                                _startAutoplaySlideshow();

                                $this.removeClass('atr-play').addClass('atr-pause').attr('data-mode', _playModePlaying);


                                if (sourceAction !== 'scrolling' && _overrideMode === 'true') {
                                    _setMultimediaPlayMode(_playModePlaying);
                                }
                            } else {
                                // We are playing, so we need to Pause the carousel
                                _writeToConsole('We are playing, so we need to Pause the carousel');
                                _currentPlayMode = _playModePaused;
                                _autoplayerIsPaused = true;

                                $this.removeClass('atr-pause').addClass('atr-play').attr('data-mode', _playModePaused);

                                if (sourceAction !== 'scrolling' && _overrideMode === 'true') {
                                    _setMultimediaPlayMode(_playModePaused);
                                }
                            }

                            break;

                        case _displayModeVideo:
                            _writeToConsole('We are displaying video');

                            // If we are in scrolling mode, we only consider acting if the data-play-mode is true
                            if (sourceAction === 'scrolling' && $('.atr-multimedia-viewer').attr('data-play-mode') === 'paused') {
                                return;
                            }

                            if ($this.hasClass('atr-play')) {
                                // We are paused, so we need to un-pause the video
                                _writeToConsole('We are paused, so we need to un-pause the video');
                                _currentPlayMode = _playModePlaying;

                                _playVideo();
                                $this.removeClass('atr-play').addClass('atr-pause');

                                if (sourceAction !== 'scrolling' && _overrideMode === 'true') {
                                    _setMultimediaPlayMode(_playModePlaying);
                                }
                            } else {
                                // We are playing, so we need to Pause the video
                                _writeToConsole('We are paused, so we need to pause the video');
                                _currentPlayMode = _playModePaused;

                                _pauseVideo();
                                $this.removeClass('atr-pause').addClass('atr-play');

                                if (sourceAction !== 'scrolling' && _overrideMode === 'true') {
                                    _setMultimediaPlayMode(_playModePaused);
                                }
                            }
                            break;

                        default:
                            break;
                    }

                });

        //-----------------------------------------------------------------------------
        // The .atr-multimedia-mode-selector switched between Images and Video modes
        //-----------------------------------------------------------------------------
        $('.atr-multimedia-mode-selector')
            .on('click',
                function (e) {
                    e.preventDefault();

                    var $this = $(this);
                    if ($this.hasClass('selected')) return;

                    // Remove the selected class from all the selectors
                    $(this).parent().find('.atr-multimedia-mode-selector').removeClass('selected');

                    // Set this selector to be selected
                    $this.addClass('selected');

                    // Set the play/pause control's data-mode
                    var selectedMode = $this.attr('data-mode');
                    var controlObject = $(this).parent().find('.atr-multimedia-control')[0];
                    $(controlObject).attr('data-mode', selectedMode);

                    _currentDisplayMode = selectedMode;
                    _setMultimediaDisplayMode(selectedMode);

                    if (selectedMode === 'video') {

                        _writeToConsole('selected display mode is Video');
                        _switchToVideo();

                        // If we are switching to video, start playing the video
                        switch (_currentPlayMode) {
                            case _playModePlaying:
                                _playVideo();
                                $(controlObject).removeClass('atr-play').addClass('atr-pause');
                                break;

                            case _playModePaused:
                                _pauseVideo();
                                $(controlObject).addClass('atr-play').removeClass('atr-pause');
                                break;

                        }
                    }

                    if (selectedMode === 'images') {
                        _writeToConsole('selected display mode is images');

                        _switchToImages();

                        _writeToConsole('_currentPlayMode' + _currentPlayMode);

                        switch (_currentPlayMode) {
                            case _playModePlaying:

                                // First, move to the next slide so the user sees something happening
                                //$('.atr-multimedia-viewer .atr-img-nav-button.atr-next').trigger('click');

                                // unset the autoplayer flag
                                _autoplayerIsPaused = false;

                                // start the autoplayer
                                _startAutoplaySlideshow();

                                $(controlObject).removeClass('atr-play').addClass('atr-pause').attr('data-mode', _playModePlaying);
                                break;

                            case _playModePaused:
                                _startManualSlideshow();
                                $(controlObject).addClass('atr-play').removeClass('atr-pause').attr('data-mode', _playModePaused);
                                break;
                        }
                    }
                });

        $('.atr-image-chapter-selector-wrapper > a')
            .on('click',
                function(e) {
                    e.preventDefault();
                    var chapters = $(this).closest('.atr-multimedia-viewer').find('.atr-image-chapters');
                    chapters.toggleClass('atr-opaque');
                });

        $('.atr-image-chapters')
            .on('mouseenter',
                function () {
                    _writeToConsole('mouse entering .atr-image-chapters');
                    if (!$(this).hasClass('atr-opaque')) {
                        $('.main-viewport .main-title-section').addClass('atr-opaque');
                        $('.atr-image-counter-wrapper').removeClass('atr-opaque');
                        $('.atr-image-caption-wrapper').removeClass('atr-opaque');
                        $('.atr-image-chapter-selector-wrapper').removeClass('atr-opaque');
                    }
                });

        $('.atr-image-chapters')
            .on('mouseleave',
                function () {
                    _writeToConsole('mouse leaving .atr-image-chapters');
                    $(this).addClass('atr-opaque');
                    $('.main-viewport .main-title-section').removeClass('atr-opaque');
                    $('.atr-image-counter-wrapper').addClass('atr-opaque');
                    $('.atr-image-caption-wrapper').addClass('atr-opaque');
                    $('.atr-image-chapter-selector-wrapper').addClass('atr-opaque');
                });

        $('.atr-image-chapters > ul > li')
            .on('click',
                function() {
                    var selectedChapter = $(this).text();
                    var firstChapterSlide = _.find(_slides, function (s) { return s.albumName === selectedChapter; });
                    var slideIndexToDisplay = parseInt(firstChapterSlide.slideIndex) - 1;

                    // load the image into the browser
                    _loadSlideIntoWrapper(slideIndexToDisplay, false, function () {

                        // Hide the image currently being displayed
                        $('.atr-image[data-slide-index="' + _currentSlideIndex + '"]').addClass('inactive');

                        // Display the next slide
                        $('.atr-image[data-slide-index="' + slideIndexToDisplay + '"]').removeClass('inactive');

                        _currentSlideIndex = slideIndexToDisplay;

                        // Load the caption
                        $('.atr-image-caption-wrapper > span').html(_slides[_currentSlideIndex].caption);

                        // Set the counter
                        var counterText = (_currentSlideIndex) + '/' + _slides.length;
                        $('.atr-image-counter-wrapper > span.jq-image-counter').text(counterText);

                        // Set the album chapter
                        $('.atr-image-counter-wrapper > span.jq-image-chapter').text(_slides[_currentSlideIndex].albumName);

                        _preloadNextImages();

                        $('.atr-image-chapters').trigger('mouseleave');
                    });
                    
                    
                });
    };

    var _pauseMultimediaPlayer = function (sourceAction) {

        _writeToConsole('_pauseMultimediaPlayer called (' + sourceAction + ')');

        var playPauseControl = $('.atr-multimedia-controls .atr-multimedia-control');
        if ($(playPauseControl).hasClass('atr-pause')) {
            $(playPauseControl).trigger('click', sourceAction);
        }

    };

    var _continueMultimediaPlayer = function (sourceAction) {

        _writeToConsole('_continueMultimediaPlayer called (' + sourceAction + ')');

        var playPauseControl = $('.atr-multimedia-controls .atr-multimedia-control');
        if ($(playPauseControl).hasClass('atr-play')) {

            _writeToConsole('playPauseControl button clicked');
            $(playPauseControl).trigger('click', sourceAction);
        }
    };

    var _initMultimediaViewerOnScroll = function() {

        //-----------------------------------------------------------------------------------------------------
        // if the user has scrolled down a quarter of the page, then stop the video
        //-----------------------------------------------------------------------------------------------------
        $(document).on('scroll', function () {
            var $document = $(document);
            var st = $document.scrollTop();
            var h = $(window).height();

            if (st > h / 4) {
                _pauseMultimediaPlayer('scrolling');
            }

            // If the page has scrolled back to the top, set the play status of the media back to whatever the user had selected.
            if (st === 0) {


                // If the user has previously selected Pause, then do nothing.
                _writeToConsole('_getMultimediaPlayMode = ' + _getMultimediaPlayMode());
                if (_getMultimediaPlayMode() !== _playModePaused) {

                    _continueMultimediaPlayer('scrolling');
                    //if (atr.localStorage.getMultimediaMode() === atr.localStorage.videoMode) {
                    //    playVideo();
                    //} else if (atr.localStorage.getMultimediaMode() === atr.localStorage.carouselMode) {
                    //    playCarousel(true, true);
                    //}
                }
            }
        });
    }

    var _initMultimediaViewer = function () {

        $('.main-viewport').height($(window).innerHeight() - 1);

        // Read the data from SPANs into an array
        _loadImageDataForSlideshow();

        if (_slides.length === 0) return;

        if (atr.common.userIsOnMobile() !== true) {
            _loadVideoData();
        }

        _initMultimediaViewerOnScroll();

        _determineCurrentOverrideMode();
        _determineCurrentDisplayMode();
        _determineCurrentPlayMode();

        //if (atr.common.userIsOnMobile() !== true &&_videos.length > 0) {
        //    $('.atr-videos-wrapper').height(window.innerHeight);
        //    _setupVideoPlayer();
        //}

        if (atr.common.userIsOnMobile() !== true && _videos.length > 0 && _slides.length > 0) {
            $('.atr-multimedia-controls-wrapper').show();
        } else {
            $('.atr-multimedia-controls-wrapper').hide();
        }

        // select a random inital image
        var randomStartImage = $('.atr-multimedia-viewer').attr('data-initial-random-image');
        if (randomStartImage === 'true') {
            _currentSlideIndex = _selectInitialImageIndex();
        }

        // Look to see if we have visited this page before. If so, load the most recent image viewed.
        var recentLocations = atr.localStorage.getRecentLocations();
        if (recentLocations.length > 0) {
            var lookupDataType = $('.atr-multimedia-viewer').attr('data-lookup-type');
            var lookupDataId = $('.atr-multimedia-viewer').attr('data-lookup-id');
            var matchingLocation = _.find(recentLocations, function (l) { return l.lookupDataId === lookupDataId && l.lookupDataType === lookupDataType });

            if (typeof (matchingLocation) !== 'undefined') {
                _currentSlideIndex = matchingLocation.imageId;
            }
        }


        // Load the selected slide
        _loadSlideIntoWrapper(_currentSlideIndex, true);
        
        // Pre-Load the next slides
        _preloadNextImages();

        _attachEventHandlers();


        //if (_videos.length > 0 && _slides.length > 0) {
        //    $('.atr-multimedia-controls-wrapper').show();
        //} else {
        //    $('.atr-multimedia-controls-wrapper').hide();
        //}

        _writeToConsole('initMMViewer: _currentDisplayMode = ' + _currentDisplayMode);
        switch (_currentDisplayMode) {

            case _displayModeImages:
                _writeToConsole('_initMultimediaViewer: case _displayModeImages');
                _switchToImages();

                if (_currentPlayMode === _playModePaused) {
                    _pauseMultimediaPlayer();
                    $('.atr-multimedia-control').removeClass('atr-pause').addClass('atr-play');
                } else {
                    _startAutoplaySlideshow();
                    $('.atr-multimedia-control').removeClass('atr-play').addClass('atr-pause');
                }

                if (atr.common.userIsOnMobile() === true || atr.common.userIsOnTablet() === true) {
                    if ($('.atr-page-wrapper').hasClass('home')) {
                        $('.atr-image-nav-controls-wrapper').hide();
                    }
                }

                $('.atr-multimedia-mode-selector[data-mode="images"]').addClass('selected');
                $('.atr-multimedia-mode-selector[data-mode="video"]').removeClass('selected');

                break;
                
            case _displayModeVideo:

                _writeToConsole('_initMultimediaViewer: case _displayModeVideo');
                _switchToVideo();

                $('.atr-multimedia-control').show();

                if (_currentPlayMode === _playModePlaying) {
                    _playVideo();
                    $('.atr-multimedia-control').removeClass('atr-play').addClass('atr-pause');
                } else {
                    _pauseVideo();
                    $('.atr-multimedia-control').removeClass('atr-pause').addClass('atr-play');
                }

                $('.atr-multimedia-mode-selector[data-mode="video"]').addClass('selected');
                $('.atr-multimedia-mode-selector[data-mode="images"]').removeClass('selected');

                break;
        }
    };


    return {

        displayModeVideo: _displayModeVideo,
        displayModeImages: _displayModeImages,

        getMultimediaDisplayMode: _getMultimediaDisplayMode,
        setMultimediaDisplayMode: _setMultimediaDisplayMode,

        initMultimediaViewer: _initMultimediaViewer,
        pauseMultimediaPlayer: _pauseMultimediaPlayer,
        continueMultimediaPlayer: _continueMultimediaPlayer
    }
})();
