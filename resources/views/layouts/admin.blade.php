<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Vite / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Header -->
            <header class="admin-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6 d-flex align-items-center gap-3">
                            <h1 class="m-0 text-2xl"><i class="bi bi-scissors text-barber-gold"></i> BarberShop</h1>
                        </div>
                        <div class="col-md-6 d-flex justify-end">
                            <h2 class="sr-only">@yield('header-title')</h2>
                        </div>
                    </div>
                </div>
            </header>

            <nav class="bg-white border-b border-gray-200 px-4 py-2">
                <div class="container-fluid d-flex gap-2">
                    <a href="{{ route('admin.panel') }}" class="nav-link-barber {{ request()->routeIs('admin.panel') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </div>
            </nav>

            <!-- Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>

        </div>
        @stack('scripts')
    </body>
</html>
