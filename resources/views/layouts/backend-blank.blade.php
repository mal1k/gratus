<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('storage/css/bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('storage/css/backend-app.css') }}">

    </head>
    <body>
        
        @yield('content')

        <!-- Scripts -->
        <script src="{{ asset('storage/js/bootstrap5.bundle.min.js') }}"></script>
    </body>
</html>
