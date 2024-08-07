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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex bg-gray-900">
        <!-- Kenar Çubuğu -->
        <aside class="bg-gray-800 w-64 min-h-screen">
            <div class="p-6">
                <h2 class="text-white text-2xl font-semibold mb-6">Admin Dashboard</h2>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center text-white hover:bg-gray-700 px-3 py-2 rounded transition">
                            <x-heroicon-o-home class="h-6 w-6 text-gray-400 mr-3" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('servers.index') }}" class="flex items-center text-white hover:bg-gray-700 px-3 py-2 rounded transition">
                            <x-heroicon-o-server class="h-6 w-6 text-gray-400 mr-3" />
                            <span>Sunucular</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('servers.create') }}" class="flex items-center text-white hover:bg-gray-700 px-3 py-2 rounded transition">
                            <x-heroicon-o-plus-circle class="h-6 w-6 text-gray-400 mr-3" />
                            <span>Yeni Sunucu</span>
                        </a>
                    </li>
                    <!-- Çıkış Butonu -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center text-white hover:bg-red-700 px-3 py-2 rounded transition w-full text-left">
                                <x-heroicon-o-arrow-right-on-rectangle class="h-6 w-6 text-gray-400 mr-3" />
                                <span>Çıkış Yap</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Ana İçerik -->
        <div class="flex-1 flex flex-col">
            <header class="bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-4 px-6 sm:px-8 lg:px-10">
                    <div class="flex justify-between items-center">
                        <h1 class="text-xl text-gray-100 font-semibold">{{ $header ?? 'Başlık' }}</h1>
                        <!-- Ekstra başlık içeriği -->
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 bg-gray-900">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
