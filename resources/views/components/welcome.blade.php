<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="zxx">
<!--<![endif]-->

<head>
    <!--====== USEFULL META ======-->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Coming Soon Ultimate HTML5 Coming Soon Template For Your Ultimate Business.">
    <meta name="keywords" content="Coming Soon,Black, Personal, Portfolio, Agency, Onepage, Html, Business, Parallax, Perticle, Video">

    <!--====== TITLE TAG ======-->
    <title>{{ config('app.name') }}</title>

    <!--====== FAVICON ICON =======-->
    <link rel="icon" type="image/icon" href="{{ asset('img/favicon.ico') }}" id="page_favicon">

    <!--====== STYLESHEETS ======-->
    <link rel="stylesheet" href="{{ asset('coming-soon/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('coming-soon/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('coming-soon/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('coming-soon/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('coming-soon/css/typed.css') }}">
    <link href="{{ asset('coming-soon/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('coming-soon/css/font-awesome.min.css') }}" rel="stylesheet">

    <!--====== MAIN STYLESHEETS ======-->
    <link href="{{ asset('coming-soon/style.css') }}" rel="stylesheet">
    <link href="{{ asset('coming-soon/css/responsive.css') }}" rel="stylesheet">

    <script src="{{ asset('coming-soon/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="transparent-layer">

    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!--- PRELOADER -->
    <div class="preeloader">
        <div class="preloader-spinner">
            <img src="{{ asset('coming-soon/img/loading.svg') }}" alt="">
        </div>
    </div>

    <!--START MAIN AREA-->
    <div class="main-area" id="home">
        <div class="main-area-bg"></div>

        <!--WELCOME AREA CONTENT-->
        <div class="welcome-text-area">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 col-sm-12 col-xs-12">
                        <div class="welcome-text text-center">
                            <img style="width:150px;" src="{{ asset('img/logo.png') }}" height="70" alt="">
                            <div class="clock-countdown">
                                <div class="site-config" data-date="{{ date('m/d/Y H:i:s', strtotime(config('app.lunch_time'))) }}" data-date-timezone="+3"></div>
                                <div class="days-counter">
                                    <div class="digit">
                                        <span style="color:#ff8000;" class="days">00</span>
                                        <span class="txt">days</span>
                                    </div>
                                </div>
                                <div class="hour-counter">
                                    <div class="border"></div>
                                    <span class="hours">00</span><span class="normal">h</span>
                                    <span class="minutes">00</span><span class="normal">min</span>
                                    <span class="seconds">00</span><span class="normal">s</span>
                                </div>
                            </div>
                            <h1 class="visible-xs">Coming Soon</h1>
                            <h3 class="hidden-xs cd-headline clip is-full-width">
                                <span class="cd-words-wrapper">
                                    <b class="is-visible">We are upgrading our website</b>
                                    <b>We will be back soon</b>
                                </span>
                            </h3>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="social-book-mark">
            <ul>
                <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-pinterest"></i></a></li> -->
            </ul>
        </div>
        <div class="information-contact">
            <ul>
                <!-- <li><a class="contact-button" href="#"><i class="fa fa-envelope-o"></i></a></li>
                <li><a class="info-button" href="#"><i class="fa fa-info"></i></a></li> -->
            </ul>
        </div>
        <!--WELCOME AREA CONTENT END-->



    </div>
    <!--END MAIN AREA-->

    <!--====== SCRIPTS JS ======-->
    <script src="{{ asset('coming-soon/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('coming-soon/js/vendor/bootstrap.min.js') }}"></script>

    <!--====== PLUGINS JS ======-->
    <script src="{{ asset('coming-soon/js/vendor/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('coming-soon/js/vendor/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ asset('coming-soon/js/typed.js') }}"></script>
    <script src="{{ asset('coming-soon/js/jquery.downCount.js') }}"></script>
    <script src="{{ asset('coming-soon/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('coming-soon/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('coming-soon/js/jquery.ajaxchimp.js') }}"></script>
    <script src="{{ asset('coming-soon/js/contact-form.js') }}"></script>

    <!--===== ACTIVE JS=====-->
    <script src="{{ asset('coming-soon/js/main.js') }}"></script>
</body>

</html>
