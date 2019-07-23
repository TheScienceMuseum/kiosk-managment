<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- auto refresher -->
    <meta http-equiv="refresh" content="1">

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
        <h3 class="text-muted">Please wait while your preview screen is loading - this may a few moments</h3>
        <div class="progress" style="height: 40px;">
            <div role="progressbar"
                 class="progress-bar progress-bar-striped progress-bar-animated"
                 aria-valuenow="{{ $preview->package_version->progress }}"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width: {{ $preview->package_version->progress }}%;"
            ></div>
        </div>
    </div>

</div>
</body>
</html>
