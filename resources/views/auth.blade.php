<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <base href="<?= url('/');?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
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
        <div class="bg-white text-dark d-flex align-items-center h-100">
            <div class="main-container-middle bg-tanzania" style="overflow:auto;">
                <div class="row align-items-center m-0 h-100">
                    <div class="col-12  col-md-6 d-none d-md-flex text-center">
                        <div class="w-80 m-auto">
                            <div class="clearfix py-4">
                                <h1>{{ config('app.name') }}</h1>
                            </div>
                            <div class="clearfix">
                                <p>{{ config('app.name') }} Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi 
                                ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit 
                                in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui 
                                officia deserunt mollit anim id est laborum.</p>
                            </div>
                        </div>  
                    </div>
                    <div class="col border-left border-white">
                        <div class="clearfix w-75 m-auto">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?= asset('js/popper.min.js');?>"></script>
    <script type="text/javascript" src="<?= asset('js/bootstrap.min.js');?>"></script>
    <script type="text/javascript" src="<?= asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js');?>"></script>
    <script type="text/javascript" src="<?= asset('js/custom.js');?>"></script>
</body>

</html>