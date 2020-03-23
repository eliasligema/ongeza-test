<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title id="page_title">@yield('title') - {{ config('app.name') }}</title>
    <base href="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrftoken: '{{ csrf_token() }}' }</script>

    <link rel="icon" type="image/icon" href="{{ asset('img/favicon.ico') }}" id="page_favicon">
    <link href="https://fonts.googleapis.com/css?family=Monoton|Aladin|Berkshire+Swash|Poppins:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('js/multimedia/multimedia.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('js/jquery-1.11.0.js') }}"></script>

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
    <script> 
    window.base_url = '<?= url('/');?>';
    window.page_title = '@yield('title')';
    window.page_name = '{{ config('app.name') }}';
    window.page_notification = {{ Helper::notifications() }};
    window.page_favicon = "{{ asset('img/favicon.png') }}";
    window.page_favicon_dot = "{{ asset('img/favicon_dot.png') }}";
    </script>

    <style>
    
    .alert-container {
        height: 48px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 999;
    }
    
    .alert-container ~ .header-menu {
        top: 48px;
    }

    .alert-container ~ .home-search-section, 
    .alert-container ~ .page-search-section {
        min-height: 150px;
    }

    .alert-container ~ .page-search-section .page-search {
        padding-top: 168px;
    }

    .alert-container ~ .page-search-section {
        margin-top: 48px;
    }

    .blowing {
        animation: blowing 2s infinite;
    }

    @keyframes blowing {
        0% {
            box-shadow: 0 0 5px red;
        }
        80% {
            box-shadow: 0 0 10px red;
        }
        100% {
            box-shadow: 0 0 5px red;
        }
    }
    </style>
</head>

<body class="bg-white default-font">
<div class="header-menu clearfix bg-white" style="z-index:20;">
    <div class="container-fluid bg-primary">
        <div class="container text-white">
            <nav class="navbar navbar-expand-lg navbar-dark px-0">
                <a class="navbar-brand" href="{{ url('/') }}">

                    @if(false)
                    @if(false)
                    <img class="e-dark" src="{{ asset('img/c-sema-logo.png') }}" height="70" alt="">
                    <img class="e-light" src="{{ asset('img/c-sema-logo.png') }}" height="70" alt="">
                    @endif
                    <img src="{{ asset('img/logo.svg') }}" height="70" alt="">
                    @endif
                    <span>{{ config('app.name') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars text-white"></span>
                </button>

                <div class="collapse navbar-collapse text-white">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mb-3 mb-lg-0">
                            <a class="nav-link ml-lg-0" href="javascript:;">Phone: +{{ config('app.phone') }}</a>
                        </li>
                        <li class="nav-item mb-3 mb-lg-0">
                            <a class="nav-link " href="javascript:;">Email: {{ config('app.email') }}</a>
                        </li>
                        <li class="nav-item dropdown ml-0 ml-lg-3">
                            <a class="nav-link text-white rounded px-2" href="javascript:;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="dropdown-toggle mx-1">
                                    {{ Helper::trans('header.language', 'Language') }}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right px-2" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item px-1" href="{{ url('switch-languege/sw') }}">Swahili (SW)</a>
                                <a class="dropdown-item px-1" href="{{ url('switch-languege/en') }}">English (EN)</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light p-0 py-lg-2">
            <div class="collapse navbar-collapse text-muted" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ml-0 ml-lg-3 mb-3 mb-lg-0">
                        <a class="nav-link {{ Request::is('/')? 'active':'' }}" href="{{ url('/') }}">{{ Helper::trans('header.home', 'Home') }}</a>
                    </li>
                    <li class="nav-item  {{ (Request::is('about') || Request::is('team'))? 'active':'' }} ml-0 ml-lg-3 mb-3 mb-lg-0 dropdown">
                        <span class="nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Helper::trans('header.about', 'About') }}
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url('about') }}">{{ Helper::trans('header.organization_profile', 'Organization Profile') }}</a>
                            <a class="dropdown-item" href="{{ url('team') }}">{{ Helper::trans('header.team', 'Our Team') }}</a>
                        </div>
                    </li>
                    <li class="nav-item ml-0 ml-lg-3 mb-3 mb-lg-0">
                        <a class="nav-link {{ Request::is('contact')? 'active':'' }}" href="{{ url('contact') }}" >{{ Helper::trans('header.contact', 'Contact') }}</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    @if(Auth::check())
                    <li class="nav-item dropdown ml-0 ml-lg-3">

                        <a class="d-flex align-items-center justify-content-between bg-white text-muted rounded mr-0" href="javascript:;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="dropdown-toggle mx-3">
                                {{ Auth::user()? substr(Auth::user()->fullname(), 0, 8).'...': null }}
                            </span>
                            <div class="image-profile rounded-circle border" style="background:url('{{ Auth::user()? Auth::user()->profile_image: null }}');width:32px;height:32px;"></div>
                            @if((Helper::notifications()) || true)
                            <span  class="badge badge-danger badge-top blowing badge-notification" style="right:-6px;top:-4px;height:16px;font-weight:400;font-size:10px;">{{ (Helper::notifications())? (Helper::notifications()):'' }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            
                            <a class="dropdown-item px-2" href="{{ url('user-profile/details') }}">{{ Helper::trans('header.profile', 'Profile') }}</a>
                            
                            <a class="dropdown-item col px-2" href="{{ url('admin/notifications') }}" style="">
                                {{ Helper::trans('header.notifications', 'Notifications') }}
                                @if((Helper::notifications()) || true)
                                <span class="badge badge-danger badge-top blowing badge-notification" style="height:16px;font-weight:400;font-size:10px;right:10px;top:7px;">{{ (Helper::notifications())? Helper::notifications():'' }}</span>
                                @endif
                            </a>
                            <a class="dropdown-item px-2" href="{{ url('logout') }}">{{ Helper::trans('header.log_out', 'Log out') }}</a>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>

    </div>
</div>
