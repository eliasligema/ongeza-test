var atr = atr || {};

atr.debug = (function() {

    // These are constants that can be set so that debug statements are written to the browser's javascript console.
    // All ATR javascript files should have the constant set here.
    var _writeToConsoleForAtrCommon = false;
    var _writeToConsoleForAtrCommonComponents = true;
    var _writeToConsoleForAtrComponentsBanner = false;
    var _writeToConsoleForAtrMainMenu = false;
    var _writeToConsoleForAtrMap = false;
    var _writeToConsoleForAtrMenu = false;
    var _writeToConsoleForAtrMultimedia = false;
    var _writeToConsoleForAtrSearchLodges = false;
    var _writeToConsoleForAtrSearchTrips = false;
    var _writeToConsoleForAtrTouch = false;
    var _writeToConsoleForAtrTrip = false;

    return {
        writeToConsoleForAtrCommon: _writeToConsoleForAtrCommon,
        writeToConsoleForAtrCommonComponents: _writeToConsoleForAtrCommonComponents,
        writeToConsoleForAtrComponentsBanner: _writeToConsoleForAtrComponentsBanner,
        writeToConsoleForAtrMainMenu: _writeToConsoleForAtrMainMenu,
        writeToConsoleForAtrMap: _writeToConsoleForAtrMap,
        writeToConsoleForAtrMenu: _writeToConsoleForAtrMenu,
        writeToConsoleForAtrMultimedia: _writeToConsoleForAtrMultimedia,
        writeToConsoleForAtrSearchLodges: _writeToConsoleForAtrSearchLodges,
        writeToConsoleForAtrSearchTrips: _writeToConsoleForAtrSearchTrips,
        writeToConsoleForAtrTouch: _writeToConsoleForAtrTouch,
        writeToConsoleForAtrTrip: _writeToConsoleForAtrTrip
    }

})();