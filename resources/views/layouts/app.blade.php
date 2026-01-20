<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aguas del Litoral') }} - @yield('title', 'Sistema de Repartos')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen">
        
        <!-- Sidebar Desktop -->
        <aside 
            :class="sidebarCollapsed ? 'w-20' : 'w-64'"
            class="fixed top-0 left-0 h-full bg-white border-r border-slate-200 transition-all duration-300 z-40 hidden lg:block"
        >
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img 
                        src="{{ asset('images/logo-aguas-del-litoral.png') }}" 
                        alt="Aguas del Litoral" 
                        class="w-10 h-10 object-contain flex-shrink-0"
                    >
                    <div x-show="!sidebarCollapsed" class="overflow-hidden">
                        <h1 class="text-lg font-bold text-slate-900">Aguas del Litoral</h1>
                        <p class="text-xs text-slate-500">Agua de Mesa</p>
                    </div>
                </a>
                <button 
                    @click="sidebarCollapsed = !sidebarCollapsed"
                    class="p-1.5 rounded-lg hover:bg-slate-100 transition-colors"
                >
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed">Dashboard</span>
                </a>

                <!-- Repartos -->
                <a href="{{ route('repartos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('repartos.*') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed">Repartos</span>
                </a>

                <!-- Clientes -->
                <a href="{{ route('clientes.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('clientes.*') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed">Clientes</span>
                </a>

                @if(auth()->user()->role !== 'repartidor')
                <!-- Pagos -->
                <a href="{{ route('pagos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('pagos.*') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed">Pagos</span>
                </a>
                @endif

                <!-- Productos -->
                <a href="{{ route('productos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('productos.*') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed">Productos</span>
                </a>

                @if(auth()->user()->role !== 'repartidor')
                <!-- Reportes -->
                <a href="#" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 transition-all">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed">Reportes</span>
                </a>
                @endif
            </nav>
        </aside>

        <!-- Mobile Sidebar -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"
            style="display: none;"
        ></div>

        <!-- Main Content -->
        <div 
            :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-64'"
            class="transition-all duration-300"
        >
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200">
                <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                    <!-- Mobile Menu Button -->
                    <button 
                        @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg hover:bg-slate-100 lg:hidden"
                    >
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Breadcrumbs -->
                    <div class="hidden md:flex items-center gap-2 text-sm">
                        @yield('breadcrumbs')
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <button class="relative p-2 rounded-lg hover:bg-slate-100 transition-colors">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open"
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 transition-colors"
                            >
                                <div class="hidden sm:block text-right">
                                    <p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                </div>
                            </button>

                            <!-- Dropdown -->
                            <div 
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1"
                                style="display: none;"
                            >
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-6">
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset
                
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <x-toast />

    @stack('scripts')
</body>
</html>
