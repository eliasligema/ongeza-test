

var atr = atr || {};

//***********************************************************************************************
// This namespace handles all interactions with local storage
//***********************************************************************************************
atr.localStorage = (function () {

    // The storageObject is the variable used throughout. Thus, if we want to change it, we need only do so here
    var storageObject = sessionStorage;

    var videoMode = 'video';
    var carouselMode = 'carousel';
    var imageMode = 'images';
    

    // If the customer has pressed play or pause, the customerSelectedMediaMode will be either mediaPlaying or mediaStopped
    var customerSelectedMediaMode = '';

    var carouselNotPresent = 'carouselNotPresent';
    var carouselPlaying = 'carouselPlaying';
    var carouselStopped = 'carouselStopped';

    var mediaPlaying = 'media_playing';
    var mediaStopped = 'media_stopped';

    var interimMediaStatusStorageKey = 'atr_interim_media_status';
    var multimediaModeStorageKey = 'atr_multimedia_mode';
    var customerSelectedMediaModeStorageKey = 'atr_customer_selected_media_mode';
    var customerSelectedMediaPlayModeStorageKey = 'atr_customer_selected_media_play_mode';

    var carouselStateStorageKey = 'atr_carousel_state';
    var carouselImageIndexStorageKey = 'atr_current_carousel_image_index';
    var carouselTextIndexStorageKey = 'atr_current_carousel_text_index';

    var lodgeMarkersStorageKey = 'atr_lodge_markers';
    var kiliMarkersStorageKey = 'atr_kili_markers';
    var lodgeRatingsLoadedStorageKey = 'atr_lodge_ratings_loaded_flags';
    var mapSettingsStorageKey = 'atr_map_settings';
    var directoryHtmlStorageKey = 'atr_directory_html';
    var selectedExperienceStorageKey = 'atr_selected_experience';
    var recentLocationsStorageKey = 'atr_recent_locations';

    var defaultLat = -18.34478487508836;
    var defaultLng = 29.916015625;
    var defaultZoom = 4;
    var defaultMenuMode = false;
    var defaultExperience = { 'facetId': 0, 'facetSubId': 0 };

    var salesCommentKey = 'atr_sales_comment';

    var bestLodgesWidgetPositionStorageKey = 'atr_best_lodges_widget_position';
    
    var mapSettingsType = function() {

        var settings = {
            "zoomLevel": defaultZoom,
            "centerLat": defaultLat,
            "centerLng": defaultLng,
            "menuMode": defaultMenuMode,
            "selectedLocation": {
                "mapId": 0,
                "name": "",
                "nodePath": "",
                "rating": 0
            },
            "locationIsDirty": true
        };

        // Retrieves the stored zoom level - or stores and returns the default
        var getZoomLevel = function () {
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                if (storedSettings.length > 0) {
                    var obj = JSON.parse(storedSettings);
                    return obj.zoomLevel;
                }
            }

            setZoomLevel(defaultZoom);
            return defaultZoom;
        };

        // Retrieves the latitude of the map center - or stores and returns the default
        var getCenterLatitude = function () {
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                if (storedSettings.length > 0) {
                    var obj = JSON.parse(storedSettings);
                    return obj.centerLat;
                }
            }

            setCenterLatitude(defaultLat);
            return defaultLat;
        };

        // Retrieves the longitude of the map center - or stores and returns the default
        var getCenterLongitude = function () {
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                if (storedSettings.length > 0) {
                    var obj = JSON.parse(storedSettings);
                    return obj.centerLng;
                }
            }

            setCenterLongitude(defaultLng);
            return defaultLng;
        };

        // Retrieves the location menu mode - or stores and returns the default
        var getMenuMode = function () {
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                if (storedSettings.length > 0) {
                    var obj = JSON.parse(storedSettings);
                    return obj.menuMode;
                }
            }

            setMenuMode(defaultMenuMode);
            return defaultMenuMode;
        };

        // Retrieves the selected location - or stores and returns the default
        var getSelectedLocation = function () {
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                if (storedSettings.length > 0) {
                    var obj = JSON.parse(storedSettings);
                    return obj.selectedLocation;
                }
            }

            setSelectedLocation(0, "");
            return {
                "mapId": 0,
                "name": "",
                "nodePath": "",
                "rating": 0
            };
        };

        // Location is dirty indicates whether the current location has changed
        var getLocationIsDirty = function () {
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                if (storedSettings.length > 0) {
                    var obj = JSON.parse(storedSettings);
                    return obj.locationIsDirty;
                }
            }

            setLocationIsDirty(true);
            return true;
        };

        // Set and store the Zoom Level
        var setZoomLevel = function(z) {
            settings.zoomLevel = z;
            storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
        }

        // Set and store the latitude of the map center
        var setCenterLatitude = function (lat) {
            settings.centerLat = lat;
            storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
        }

        // Set and store the longitude of the map center
        var setCenterLongitude = function (lng) {
            settings.centerLng = lng;
            storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
        }

        // Set and store the location menu mode
        var setMenuMode = function (m) {
            settings.menuMode = m;
            storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
        }

        // Set and store the selected location
        var setSelectedLocation = function (mapId, name, nodePath, rating) {

            // Check to see if we have changed location
            if (mapId != settings.selectedLocation.mapId) {
                setLocationIsDirty(true);
            }

            settings.selectedLocation.mapId = mapId;
            settings.selectedLocation.name = name;
            settings.selectedLocation.nodePath = nodePath;
            // added rating to this so that the map can load the map markers for the rating level if necessasry
            settings.selectedLocation.rating = rating;
            storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
        }

        // Set and store the Location is dirty flag
        var setLocationIsDirty = function (isDirty) {
            settings.locationIsDirty = isDirty;
            storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
        };

        var initialise = function () {
            //console.log("initMapSettings starting");
            var storedSettings = storageObject.getItem(mapSettingsStorageKey);
            if (storedSettings) {
                //console.log("stored settings found: " + storageObject.getItem(mapSettingsStorageKey));
                settings = JSON.parse(storageObject.getItem(mapSettingsStorageKey));
            } else {
                storageObject.setItem(mapSettingsStorageKey, JSON.stringify(settings));
                //console.log("new settings created and stored: " + storageObject.getItem(mapSettingsStorageKey));
            }
        };

        return {
            getZoomLevel: getZoomLevel,
            getCenterLatitude: getCenterLatitude,
            getCenterLongitude: getCenterLongitude,
            getMenuMode: getMenuMode,
            getSelectedLocation: getSelectedLocation,
            getLocationIsDirty: getLocationIsDirty,
            setZoomLevel: setZoomLevel,
            setCenterLatitude: setCenterLatitude,
            setCenterLongitude: setCenterLongitude,
            setMenuMode: setMenuMode,
            setSelectedLocation: setSelectedLocation,
            setLocationIsDirty: setLocationIsDirty,
            initialise: initialise
        }
    }

    var mapSettings = new mapSettingsType();
    mapSettings.initialise();

    //****************************************************************************
    // Retrieves the status of the media that was playing most recently
    //****************************************************************************
    var getInterimMediaStatus = function() {

        var interimMediaStatusStorageValue = storageObject.getItem(interimMediaStatusStorageKey);
        if (interimMediaStatusStorageValue) {
            if (interimMediaStatusStorageValue.length > 0) {
                return JSON.parse(interimMediaStatusStorageValue);
            }
        }

        return { 'currentMode': atr.localStorage.imageMode, 'playMode': atr.localStorage.mediaStopped };
    };

    //****************************************************************************
    // Stores the status of the media that was playing most recently
    //****************************************************************************
    var setInterimMediaStatus = function (currentMode, playMode) {
        storageObject.setItem(interimMediaStatusStorageKey,  JSON.stringify({ 'currentMode': currentMode, 'playMode': playMode }));
    };

    //****************************************************************************
    // Retrieves the multimedia mode - whether we are displaying video or images on the home page
    // OBSOLETE declaration.
    //****************************************************************************
    //var getMultimediaMode = function() {

    //    var multimediaModeStorageValue = storageObject.getItem(multimediaModeStorageKey);
    //    if (multimediaModeStorageValue) {
    //        if (multimediaModeStorageValue.length > 0) {
    //            return multimediaModeStorageValue;
    //        }
    //    }

    //    return videoMode;
    //}

    //****************************************************************************
    // Stores the multmedia mode in local storage
    // OBSOLETE declaration
    //****************************************************************************
    //var setMultimediaMode = function (multimediaMode) {
    //    storageObject.setItem(multimediaModeStorageKey, multimediaMode);
    //}

    //****************************************************************************
    // Retrieves the customer's multimedia mode - whether we are displaying video or images on the home page
    //****************************************************************************
    var getCustomerSelectedMultimediaMode = function () {

        var storageValue = storageObject.getItem(customerSelectedMediaModeStorageKey);
        if (storageValue) {
            if (storageValue.length > 0) {
                return storageValue;
            }
        }

        return atr.localStorage.carouselMode;
    }

    //****************************************************************************
    // Stores the customer's multmedia mode in local storage
    //****************************************************************************
    var setCustomerSelectedMultimediaMode = function (multimediaMode) {
        storageObject.setItem(customerSelectedMediaModeStorageKey, multimediaMode);
    }

    //****************************************************************************
    // Retrieves the multimedia play mode - whether the media is playing or stopped
    //****************************************************************************
    var getCustomerMultimediaPlayMode = function () {

        var storageValue = storageObject.getItem(customerSelectedMediaPlayModeStorageKey);
        if (storageValue) {
            if (storageValue.length > 0) {
                return storageValue;
            }
        }

        return '';
    }

    //****************************************************************************
    // Stores the multmedia play mode in local storage
    //****************************************************************************
    var setCustomerMultimediaPlayMode = function (multimediaMode) {
        storageObject.setItem(customerSelectedMediaPlayModeStorageKey, multimediaMode);
    }

    // 
    //****************************************************************************
    // Retrieves the state of the carousel - playing or stopped
    //****************************************************************************
    var getCarouselState = function () {

        var carouselStateStorageValue = storageObject.getItem(carouselStateStorageKey);
        if (carouselStateStorageValue) {
            if (carouselStateStorageValue.length > 0) {
                return carouselStateStorageValue;
            }
        }

        return carouselStopped;
    }

    //****************************************************************************
    // Stores the state of the carousel in local storage
    //****************************************************************************
    var setCarouselState = function (carouselState) {
        storageObject.setItem(carouselStateStorageKey, carouselState);
    }

    // 
    //****************************************************************************
    // Retrieves the index of the text slide to be displayed on the carousel
    //****************************************************************************
    var getCurrentCarouselImageIndex = function () {

        var storageValue = storageObject.getItem(carouselImageIndexStorageKey);
        if (storageValue) {
            if (storageValue.length > 0) {
                return parseInt(storageValue);
            }
        }

        return 0;
    }

    //****************************************************************************
    // Stores the index of the image slide to be displayed on the carousel
    //****************************************************************************
    var setCurrentCarouselImageIndex = function (carouselImageIndex) {
        storageObject.setItem(carouselImageIndexStorageKey, carouselImageIndex);
    }

    // 
    //****************************************************************************
    // Retrieves the index of the text slide to be displayed on the carousel
    //****************************************************************************
    var getCurrentCarouselTextIndex = function () {

        var storageValue = storageObject.getItem(carouselTextIndexStorageKey);
        if (storageValue) {
            if (storageValue.length > 0) {
                return parseInt(storageValue);
            }
        }

        return 0;
    }

    //****************************************************************************
    // Stores the index of the text slide to be displayed on the carousel
    //****************************************************************************
    var setCurrentCarouselTextIndex = function (carouselTextIndex) {
        storageObject.setItem(carouselTextIndexStorageKey, carouselTextIndex);
    }

    //****************************************************************************
    // We need to store in storage the flags that reflect which lodge ratings have already been loaded
    //****************************************************************************
    var getLodgeRatingsLoadedFlagsFromStorage = function () {

        var lodgeLoadedFlagsStorageValue = storageObject.getItem(lodgeRatingsLoadedStorageKey);
        if (lodgeLoadedFlagsStorageValue) {
            if (lodgeLoadedFlagsStorageValue.length > 0) {
                return JSON.parse(lodgeLoadedFlagsStorageValue);
            }
        }

        return new Array(11);
    };

    //****************************************************************************
    // Stores the lodge rating loaded flags in local storage
    //****************************************************************************
    var storeLodgeRatingsLoadedFlagsInStorage = function () {
        storageObject.setItem(lodgeRatingsLoadedStorageKey, JSON.stringify(atr.map.data.lodgeRatingsLoaded));
    }

    //****************************************************************************
    // Retrieves all the lodge markers stored in local storage
    //****************************************************************************
    var getLodgeMarkersFromStorage = function () {

        var lodgeMarkersStorageValue = storageObject.getItem(lodgeMarkersStorageKey);
        if (lodgeMarkersStorageValue) {
            if (lodgeMarkersStorageValue.length > 0) {
                return JSON.parse(lodgeMarkersStorageValue);
            }
        }

        return [];
    };

    //****************************************************************************
    // Stores the specified data into local storage as the Lodge Markers
    //****************************************************************************
    var storeLodgeMarkersInStorage = function (jsonData) {
        storageObject.setItem(lodgeMarkersStorageKey, JSON.stringify(jsonData));
    };

    //****************************************************************************
    // Retrieves all the kili markers stored in local storage
    //****************************************************************************
    var getKiliMarkers = function() {

        var kiliMarkersStorageValue = storageObject.getItem(kiliMarkersStorageKey);
        if (kiliMarkersStorageValue) {
            if (kiliMarkersStorageValue.length > 0) {
                return JSON.parse(kiliMarkersStorageValue);
            }
        }

        return [];
    }

    //****************************************************************************
    // Stores the specified data into local storage as the kili Markers
    //****************************************************************************
    var storeKiliMarkers = function (jsonData) {
        storageObject.setItem(kiliMarkersStorageKey, JSON.stringify(jsonData));
    };

    //****************************************************************************
    // The directory html is the raw code for the location tree directory
    //****************************************************************************
    var getDirectoryHtml = function() {

        var directoryHtmlStorageValue = storageObject.getItem(directoryHtmlStorageKey);
        if (directoryHtmlStorageValue) {
            if (directoryHtmlStorageValue.length > 0) {
                return directoryHtmlStorageValue;
            }
        }

        return '';
    };

    var setDirectoryHtml = function (html) {
        storageObject.setItem(directoryHtmlStorageKey, html);
    };


    //****************************************************************************
    // The selected experience from the experience menu needs a facetid and facetsubid
    //****************************************************************************
    var getSelectedExperience = function () {

        var selectedExperienceStorageValue = storageObject.getItem(selectedExperienceStorageKey);
        if (selectedExperienceStorageValue) {
            if (selectedExperienceStorageValue.length > 0) {
                return JSON.parse(selectedExperienceStorageValue);
            }
        }

        return defaultExperience;
    };

    var setSelectedExperience = function(facetId, facetSubId) {
        var x = { 'facetId': facetId, 'facetSubId': facetSubId };
        storageObject.setItem(selectedExperienceStorageKey, JSON.stringify(x));
    };


    //****************************************************************************
    // Recent location details pages visited are stored with the most recent 
    // slideshow image displayed, so it can be redisplayed if the user revisits
    //****************************************************************************
    var getRecentLocations = function() {

        var selectedRecentLocationsValue = storageObject.getItem(recentLocationsStorageKey);
        if (selectedRecentLocationsValue) {
            if (selectedRecentLocationsValue.length > 0) {
                return JSON.parse(selectedRecentLocationsValue);
            }
        }

        return [];
    };

    var setRecentLocationImage = function(lookupDataId, lookupDataType, imageId) {

        if (lookupDataType !== 'Experience' && lookupDataType !== 'Location') return;

        var x = { 'lookupDataId': lookupDataId, 'lookupDataType': lookupDataType, 'imageId': imageId };

        var recentLocations = getRecentLocations();
        var matchingLocation = _.find(recentLocations, function (l) { return l.lookupDataId === lookupDataId && l.lookupDataType === lookupDataType });

        if (typeof(matchingLocation) == 'undefined') {
            recentLocations.push(x);
        } else {
            matchingLocation.imageId = imageId;
        }
        
        storageObject.setItem(recentLocationsStorageKey, JSON.stringify(recentLocations));
    };

    //****************************************************************************
    // Retrieves the last sales comment displayed so the page can start from the next one
    //****************************************************************************
    var getSalesCommentIndex = function (count) {
        var salesCommentIndex = storageObject.getItem(salesCommentKey);
        if (!salesCommentIndex) {
            salesCommentIndex = Math.floor(Math.random() * count); // start from a random comment between 0 and count
        } else {
            setSalesCommentIndex(++salesCommentIndex); // start from the next comment
        }

        return salesCommentIndex;
    };

    //****************************************************************************
    // Stores the current sales comment index
    //****************************************************************************
    var setSalesCommentIndex = function (salesCommentIndex) {
        storageObject.setItem(salesCommentKey, salesCommentIndex);
    };

    var getBestLodgesWidgetPositionForUrl = function(url) {

        var value = storageObject.getItem(bestLodgesWidgetPositionStorageKey);
        if (value) {
            if (value.length > 0) {
                var obj = JSON.parse(value);
                if (obj.url == url) {
                    return obj.position;
                }
            }
        }

        return 0;
    };

    var setBestLodgesWidgetPositionForUrl = function(url, position) {
        var obj = { 'url': url, 'position': position };
        storageObject.setItem(bestLodgesWidgetPositionStorageKey, JSON.stringify(obj));
    };

    var imageNavCookieName = 'ATRImageNav';
    var userHasClickedImageNav = function () {
        var c = $.cookie(imageNavCookieName);
        return c != null;
    };

    var setUserHasClickedImageName = function() {

        // Store the search model in a cookie - cookie lasts forever (arbitrary atm)
        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000 * 24 * 1000; // 1000 days
        now.setTime(time);

        $.cookie(imageNavCookieName, true, { expires: now, path: '/' });
    };

    var tripSearchResultsDisplayCountCookieName = 'ATRTripSearchResultsDisplayCount';

    var getTripSearchResultsDisplayCount = function () {
        var c = $.cookie(tripSearchResultsDisplayCountCookieName);
        return c == null ? 6 : c;
    };

    var setTripSearchResultsDisplayCount = function(displayCount) {
        var now = new Date();
        var time = now.getTime();
        time += 3600 * 1000 * 24; // 1 day
        now.setTime(time);

        $.cookie(tripSearchResultsDisplayCountCookieName, displayCount, { expires: now, path: '/' });
    }




    return {
        videoMode: videoMode,
        carouselMode: carouselMode,
        imageMode: imageMode,

        carouselNotPresent: carouselNotPresent,
        carouselPlaying: carouselPlaying,
        carouselStopped: carouselStopped,

        mediaPlaying: mediaPlaying,
        mediaStopped: mediaStopped,

        getInterimMediaStatus: getInterimMediaStatus,
        setInterimMediaStatus: setInterimMediaStatus,

        getCustomerSelectedMultimediaMode: getCustomerSelectedMultimediaMode,
        setCustomerSelectedMultimediaMode: setCustomerSelectedMultimediaMode,

        getCustomerMultimediaPlayMode: getCustomerMultimediaPlayMode,
        setCustomerMultimediaPlayMode: setCustomerMultimediaPlayMode,

        getCarouselState: getCarouselState,
        setCarouselState: setCarouselState,

        getCurrentCarouselImageIndex: getCurrentCarouselImageIndex,
        setCurrentCarouselImageIndex: setCurrentCarouselImageIndex,

        getCurrentCarouselTextIndex: getCurrentCarouselTextIndex,
        setCurrentCarouselTextIndex: setCurrentCarouselTextIndex,

        getLodgeRatingsLoadedFlags: getLodgeRatingsLoadedFlagsFromStorage,
        storeLodgeRatingsLoadedFlags: storeLodgeRatingsLoadedFlagsInStorage,

        getLodgeMarkers: getLodgeMarkersFromStorage,
        storeLodgeMarkers: storeLodgeMarkersInStorage,
        
        getKiliMarkers: getKiliMarkers,
        storeKiliMarkers: storeKiliMarkers,

        mapSettings: mapSettings,
        getDirectoryHtml: getDirectoryHtml,
        setDirectoryHtml: setDirectoryHtml,

        getSelectedExperience: getSelectedExperience,
        setSelectedExperience: setSelectedExperience,

        getRecentLocations: getRecentLocations,
        setRecentLocationImage: setRecentLocationImage,

        getSalesCommentIndex: getSalesCommentIndex,
        setSalesCommentIndex: setSalesCommentIndex,

        getBestLodgesWidgetPositionForUrl: getBestLodgesWidgetPositionForUrl,
        setBestLodgesWidgetPositionForUrl: setBestLodgesWidgetPositionForUrl,

        userHasClickedImageNav: userHasClickedImageNav,
        setUserHasClickedImageName: setUserHasClickedImageName,

        getTripSearchResultsDisplayCount: getTripSearchResultsDisplayCount,
        setTripSearchResultsDisplayCount: setTripSearchResultsDisplayCount
    }
})();

//atr.localStorage.initMapSettings();