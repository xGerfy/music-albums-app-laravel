@extends('layouts.app')

@section('nav-title', 'Вход')

@section('content')
    <div class="max-w-sm mx-auto pt-12">
        <div class="mb-10">
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight mb-2">Вход</h1>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="Email"
                       class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all placeholder:text-gray-400">
                @error('email')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       placeholder="Пароль"
                       class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all placeholder:text-gray-400">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                <label for="remember" class="ml-2 text-sm text-gray-600">Запомнить</label>
            </div>

            <button type="submit"
                    class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-base font-medium hover:bg-gray-800 transition-colors">
                Войти
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Нет аккаунта?
                <a href="{{ route('register') }}"
                   class="text-gray-900 hover:underline">Создать</a>
            </p>
        </div>
    </div>
@endsection
