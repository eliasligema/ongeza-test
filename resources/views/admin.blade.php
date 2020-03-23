<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <base href="{{ url('/') }}">

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- <script src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> -->

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
    <script> window.base_url = '{{ url('/') }}';</script>
</head>

<body>

<!-- Start Header -->
<div class="fixed-top-header bg-primary text-white">
    <nav class="navbar navbar-expand-lg p-0">
        <div class="container-fluid p-0">
            <a href="{{ url('/') }}" class="navbar-brand d-flex h-100 p-0" title="{{ config('app.name') }}">
                <div class="brand-block d-flex align-items-center">
                    <span class="d-inline-block ml-3 text-white">{{ config('app.name') }}</span>
                    <!-- <img src="{{ asset('img/logo-white.png') }}" class="d-inline-block ml-2"> -->
                </div>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> 
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="nav justify-content-center mr-auto">
                    @if(user('role') != User::ROLE_ADMIN && session('account_id'))
                    <li class="nav-item" >
                        <span class="nav-link font-weight-bold">{{ session('account_name') }}</span>
                    </li>
                    @endif
                </ul>
                <ul class="nav justify-content-center ml-auto">
                    @if(user('role') != User::ROLE_ADMIN && session('account_id'))
                    <li class="nav-item dropdown">
                        <span class="dropdown-toggle nav-link" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Switch Account 
                            <!-- <span class="badge badge-pill badge-top badge-dark">{{ count(session('accounts_list')) }}</span> -->
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach(session('accounts_list') as $row)
                            <a class="dropdown-item" href="{{ url('switch-account/'.$row->account_id) }}">{{ $row->name }}</a>
                            @endforeach
                        </div>
                    </li>
                    @endif
                    @if(Auth::check())
                    <li class="nav-item dropdown ">
                        <span class="dropdown-toggle nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ (Auth::user())? Auth::user()->profile_image: '' }}" class="rounded img-profile rounded-circle border border-dark" alt="..."> 
                            <span>{{ (Auth::user())? Auth::user()->fullname(): '' }} </span>
                        </span>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url('user-profile/details') }}">User profile</a>
                            <a class="dropdown-item" href="{{ url('user-profile/change-password') }}">Change password</a>
                            <a class="dropdown-item" href="{{ url('logout') }}">Log out</a>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
<!-- End Header -->
        <!-- Start Sidebar Menu -->
<div class="fixed-top-sidebar bg-white"> 
    <div class="sidebar-nav scroll-container" scroll-container>
        <ul class="nav navbar-nav" accordion>
            <li class="nav-level bg-light d-none d-md-block">General modules</li>
            <li class="nav-item {{ Request::is('admin/customer/*')? 'active': null }}">
                <a class="nav-link" href="{{ url('admin/customer/list') }}">
                    <i class="fa fa-chess-board"></i> <span>Customers</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/gender/*')? 'active': null }}">
                <a class="nav-link" href="{{ url('admin/gender/list') }}">
                    <i class="fa fa-chess-board"></i> <span>Genders</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/user/*')? 'active': null }}">
                <a class="nav-link" href="{{ url('admin/user/list') }}">
                    <i class="fa fa-chess-board"></i> <span>Manage users</span>
                </a>
            </li>
        </ul>
    </div> 
</div>
<!-- End Sidebar Menu --><!-- Start Main Container -->
<div class="fixed-top-main bg-default {{ ( isset($is_verifed) && !$is_verifed)? 'verify-to-start': null }}">
    <div class="main-container-middle">
        <div class="container-detail">
            @yield('content')
        </div>
    </div>
</div>

@yield('verify-container')
<!-- End Main Container -->

<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>