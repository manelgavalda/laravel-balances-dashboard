<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @if (app()->environment() === 'production')
            <link rel="preload" as="style" href="{{ vercel_asset('build/assets/app.css')}}">
            <link rel="modulepreload" href="{{ vercel_asset('build/assets/app2.js') }}">
            <link rel="stylesheet" href="{{ vercel_asset('build/assets/app.css')}}">

            <script type="module" src="{{ vercel_asset('build/assets/app2.js') }}"></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-slate-700">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="w-11/12 mx-auto py-4">
                @inertia
            </main>
        </div>
    </body>
</html>
