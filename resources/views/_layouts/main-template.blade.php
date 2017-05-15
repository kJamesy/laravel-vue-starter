<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('/') }}/{{ mix('css/app.css') }}">
    <style>
        @media (max-width: 575px) {
            .navbar .container {
                margin: 0;
            }
        }
    </style>
    @yield('header-styles-scripts')
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
</head>
<body>

    @yield('body')

    <script src="{{ url('/') }}/{{ mix('js/manifest.js') }}"></script>
    <script src="{{ url('/') }}/{{ mix('js/vendor.js') }}"></script>
    <script src="{{ url('/') }}/{{ mix('js/app.js') }}"></script>
    @yield('footer-scripts')
</body>
</html>
