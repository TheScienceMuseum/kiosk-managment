<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('head')

    <title>{{ config('app.name', 'Kiosk Management System') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        @if($errors)
        window.errors = {!! json_encode($errors->toArray()) !!};
        @else
        window.errors = null;
        @endif
        @guest
        window.current_user = null;
        @else
        window.current_user = {
            permissions: {!! json_encode(Auth::user()->getAllPermissions()->map(function ($permission) { return [ 'name' => $permission->name ]; })) !!},
            name: {!! json_encode(Auth::user()->name) !!},
        };
        @endguest
    </script>
</body>
</html>
