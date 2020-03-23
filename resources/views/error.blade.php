<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <base href="<?= url('/');?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="icon" type="image/png" href="<?= asset('img/favicon.png');?>">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>

    @if(config('app.env') == 'production')
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_analytics') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());

        gtag('config', '{{ config('app.google_analytics') }}');
    </script>
    @endif
    <script> window.base_url = '<?= url('/');?>';</script>
</head>

<body>
<div class="fixed-top-main">
        <div class="bg-primary text-white d-flex align-items-center text-white h-100">
            <div class="main-container-middle">
                <div class="row align-items-center h-100">
                    
                    <div class="col ">
                        <div class="clearfix w-75 m-auto">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>