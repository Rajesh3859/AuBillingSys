<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AutoSpares Billing') }}</title>

        <!-- Plus Jakarta Sans Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900" x-data="{ sidebarOpen: false }">
        
        <div class="min-h-screen bg-slate-100 flex flex-col">
            
            <!-- Mobile Sidebar Backdrop Overlay -->
            <div x-show="sidebarOpen"
                 @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-30 bg-slate-950/60 backdrop-blur-sm lg:hidden"
                 style="display: none;"></div>

            <!-- Sidebar Navigation -->
            @include('layouts.sidebar')

            <!-- Main Content Container -->
            <div class="lg:pl-64 flex-1 flex flex-col min-h-screen">
                
                <!-- Top Navigation Header Bar -->
                <header class="bg-white border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                    <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                        
                        <!-- Left: Mobile Toggle & Title -->
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 focus:outline-none text-micro-label font-bold uppercase border border-slate-200">
                                Menu
                            </button>
                            
                            @isset($header)
                                <div class="flex-1">
                                    {{ $header }}
                                </div>
                            @endisset
                        </div>

                        <!-- Right: User Avatar & Quick Shortcuts -->
                        <div class="hidden sm:flex items-center gap-3">
                            <a href="{{ route('billing.index') }}" class="bg-slate-900 hover:bg-slate-800 text-white text-micro-label font-semibold px-3.5 py-1.5 rounded-lg transition-all border border-slate-800">
                                POS Terminal
                            </a>

                            <div class="h-5 w-px bg-slate-200"></div>

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-2 text-micro-label font-semibold text-slate-800 hover:text-slate-900 focus:outline-none">
                                        <div class="w-7 h-7 rounded bg-slate-900 text-white font-bold flex items-center justify-center text-micro-label">
                                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <span>{{ Auth::user()->name ?? 'User' }}</span>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="text-table-body">
                                        Profile Settings
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                                class="text-table-body">
                                            Log Out
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                    </div>
                </header>

                <!-- Page Content Slot -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
                
            </div>

        </div>
    </body>
</html>
