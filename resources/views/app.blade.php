<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="h-full m-0 bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        
        {{-- <script src="https://js.stripe.com/v3/"></script> --}}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])

        @inertiaHead

        <script>
            //window.APP_URL = "{{ config('app.url') }}";
            window.API_URL = "{{ config('api.url') }}";
        </script>
    </head>
    <body class="h-full m-0 font-sans antialiased">
        @inertia
    </body>
</html>
