<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <link rel="shortcut icon" type="image/x-icon" href="{{\App\Utils::setting(\App\Settings::SITE_FAVICON)}}">

    <title>{{\App\Utils::setting(\App\Settings::SITE_TITLE)}}</title>
    <meta name="description" content="{{\App\Utils::setting(\App\Settings::SITE_DESCRIPTION)}}">
    <meta name="keywords" content="{{\App\Utils::setting(\App\Settings::SITE_KEYWORDS)}}">

    <link href="/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/main.min.css">
    <link rel="stylesheet" href="/assets/css/auth.css">

    {!! \App\Utils::setting(\App\Settings::SITE_GOOGLE_DOMAIN_VERIFY) !!}
    {!! \App\Utils::setting(\App\Settings::SITE_BING_DOMAIN_VERIFY) !!}

    @yield('styles')
</head>

<body>
<header class="navbar navbar-header navbar-header-fixed">
    <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
    <div class="navbar-brand">
        <a href="/" class="df-logo">
            <img class="w-max-150px" width="100" src="{{\App\Utils::setting(\App\Settings::SITE_LOGO)}}"/>
        </a>
    </div>
    <div id="navbarMenu" class="navbar-menu-wrapper">
        <div class="navbar-menu-header">
            <a href="/" class="df-logo">
                <img class="w-max-150px" width="100" src="{{\App\Utils::setting(\App\Settings::SITE_LOGO)}}"/>
            </a>
            <a id="mainMenuClose" href=""><i data-feather="x"></i></a>
        </div>
    </div>
    <div class="navbar-right">
        <a href="/login" class="btn btn-buy"><i data-feather="log-in"></i> <span>Sign In</span></a>
        @if(\App\Utils::settingCan(\App\Settings::WEB_USERS_CAN_REGISTER))
            <a href="/register" class="btn btn-buy"><i data-feather="user-plus"></i> <span>Sign Up</span></a>
        @endif
    </div>
</header>

@yield('content')

<footer class="footer">
    <div>
        <span>&copy; {{Date('Y')}} {{config('app.name')}}. </span>
    </div>
</footer>

<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/lib/feather-icons/feather.min.js"></script>
<script src="/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/js/main.js"></script>
@yield('scripts')
</body>
</html>

