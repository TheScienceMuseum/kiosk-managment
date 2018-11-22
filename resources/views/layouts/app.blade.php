<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kiosk Management System') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    {{--@if (config('app.env') !== 'production')--}}
        {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0/solar/bootstrap.min.css">--}}
    {{--@endif--}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            @can('view all kiosks')
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin/packages">{{ __('packages.title') }}</a>
                                </li>
                            @endcan

                            @can('view all kiosks')
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin/kiosks">{{ __('kiosks.title') }}</a>
                                </li>
                            @endcan

                            @can('view all users')
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/users">{{ __('users.title') }}</a>
                            </li>
                            @endcan

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ auth()->user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @if($errors->count())
        <div class="container pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $message)
                            <span class="text-muted">{{ $message }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session()->exists('status'))
            <div class="container pt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            {{ session()->get('status') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        window.application_config = {
            translations: <?php
                // copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
                $lang_files = File::files(resource_path() . '/lang/' . app()->getLocale());
                $trans = [];
                foreach ($lang_files as $f) {
                    $filename = pathinfo($f)['filename'];
                    $trans[$filename] = trans($filename);
                }
                echo json_encode($trans);
                ?>,
        };

        @guest
            window.current_user = null;
        @else
            window.current_user = {
                permissions: <?php
                    echo json_encode(Auth::user()->getAllPermissions());
                ?>,
                name: <?php
                    echo json_encode(Auth::user()->name);
                ?>
            };
        @endguest
    </script>
</body>
</html>
