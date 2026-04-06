<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Справочник альбомов')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .sf-pro {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f5f5f5] min-h-screen">
{{-- Navigation --}}
<nav class="sticky top-0 z-50 bg-white/50 backdrop-blur-xl border-b border-gray-200/50">
    <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('albums.index') }}"
           class="text-xl font-semibold text-gray-900 tracking-tight">@yield('nav-title', 'Альбомы')</a>
        <div class="flex gap-3 items-center">
            @auth
                <span class="cursor-default text-sm text-gray-500">{{ Auth::user()->name }}</span>
                <div class="w-px h-6 bg-gray-300"></div>
                <form method="post" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="text-sm text-gray-500 hover:text-gray-900 transition-colors cursor-pointer">Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Войти</a>
                <div class="w-px h-6 bg-gray-300"></div>
                <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Регистрация</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Alerts --}}
@if (session('success'))
    <div class="max-w-5xl mx-auto px-6 pt-6">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="max-w-5xl mx-auto px-6 pt-6">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Main Content --}}
<main class="max-w-5xl mx-auto px-6 py-12">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
