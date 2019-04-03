<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- auto refresher -->
    <meta http-equiv="refresh" content="10">

    <title>Previewing: {{ $preview->package_version->package->name }} version {{ $preview->package_version->version }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">

    <div class="text-center" style="padding-top: 40vh;">
        <h1>Currently Building Preview</h1>
        <h3><small class="text-muted">you'll be redirected once we are finished building</small></h3>
    </div>

</div>
</body>
</html>
