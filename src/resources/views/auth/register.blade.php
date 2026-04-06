@extends('layouts.app')

@section('nav-title', 'Регистрация')

@section('content')
    <div class="max-w-sm mx-auto pt-12">
        <div class="mb-10">
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight mb-2">Регистрация</h1>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       placeholder="Имя"
                       class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all placeholder:text-gray-400">
            </div>

            <div>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
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
                @error('password')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       placeholder="Подтверждение пароля"
                       class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all placeholder:text-gray-400">
            </div>

            <button type="submit"
                    class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-base font-medium hover:bg-gray-800 transition-colors">
                Создать аккаунт
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Уже есть аккаунт?
                <a href="{{ route('login') }}"
                   class="text-gray-900 hover:underline">Войти</a>
            </p>
        </div>
    </div>
@endsection
