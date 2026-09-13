<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rajesh Billing') }}</title>

        <!-- Self-Hosted Plus Jakarta Sans (Zero external hops, parallel same-origin preload) -->
        <link rel="preload" href="/fonts/plus-jakarta-sans.woff2" as="font" type="font/woff2" crossorigin>

        <!-- Scripts & Styles -->
        @if (! Vite::isRunningHot() && file_exists(public_path('build/manifest.json')))
            <style>{!! Vite::content('resources/css/app.css') !!}</style>
        @else
            @vite(['resources/css/app.css'])
        @endif
        @vite(['resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-950">
            <div class="mb-2 text-center">
                <a href="/" class="text-xl font-bold tracking-tight text-white uppercase">
                    <span class="text-emerald-400">Rajesh</span> Pro
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-xl border border-slate-300">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
