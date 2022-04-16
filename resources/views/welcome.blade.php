<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>The Gratus homepage</title>

    </head>
    <body>

        <h1>Welcome! This is a home page of gratus website.</h1>

        @if (!auth()->guest())
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
        @endif

    </body>
</html>
