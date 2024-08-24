<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" type="image/x-icon"
          href="{{\App\Models\Utils::setting(\App\Models\Settings::SITE_FAVICON)}}">

    <title>{{\App\Models\Utils::setting(\App\Models\Settings::SITE_TITLE)}}</title>
    <meta name="description" content="{{\App\Models\Utils::setting(\App\Models\Settings::SITE_DESCRIPTION)}}">
    <meta name="keywords" content="{{\App\Models\Utils::setting(\App\Models\Settings::SITE_KEYWORDS)}}">

    @yield('pre_styles')

    <link href="/assets/css/sb-admin-2.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {!! \App\Models\Utils::setting(\App\Models\Settings::SITE_GOOGLE_DOMAIN_VERIFY) !!}
    {!! \App\Models\Utils::setting(\App\Models\Settings::SITE_BING_DOMAIN_VERIFY) !!}

    @yield('styles')
</head>
<body id="page-top">

<div id="wrapper">

    @if(auth()->user()->role === 'admin')
        @include('layout-backend.admin-sidebar')
    @endif

    @if(auth()->user()->role === 'user')
        @include('layout-backend.user-sidebar')
    @endif

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                @impersonating
                <a class="btn btn-primary btn-xs mr-4" href="{{ route('impersonate.leave') }}"><i
                        class="fas fa-sign-out-alt"></i> Go back to Account</a>
                @endImpersonating

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->name}}</span>
                            <img class="img-profile rounded-circle"
                                 src="{{auth()->user()->avatar}}">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/profile">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>

                            @if(auth()->user()->role === 'admin')
                                <a href="/admin/settings/site" class="dropdown-item">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Site Settings
                                </a>
                                <a href="/admin/settings/mailing" class="dropdown-item">
                                    <i class="fas fa-mail-bulk fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Mailing Settings
                                </a>
                            @endif

                            <div class="dropdown-divider"></div>

                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                               class="dropdown-item"><i data-feather="log-out"></i>Sign Out</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>

            </nav>

            <div class="container-fluid">
                {{ $slot }}
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Webcraft</span>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://kit.fontawesome.com/54da8bee4c.js" crossorigin="anonymous"></script>
<script src="/assets/plugins/jquery/jquery.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/plugins/jquery-easing/jquery.easing.min.js"></script>
<script src="/assets/js/sb-admin-2.min.js"></script>
@yield('scripts')
{!! \App\Models\Utils::setting(\App\Models\Settings::SITE_GOOGLE_ANALYTICS) !!}
</body>
</html>
