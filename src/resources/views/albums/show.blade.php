@extends('layouts.app')

@section('nav-title', $album->artist)

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Album Header --}}
        <div class="flex flex-col md:flex-row gap-10 mb-12">
            @if($album->cover_image_url)
                <div class="shrink-0">
                    <img src="{{ $album->cover_image_url }}"
                         alt="{{ $album->title }}"
                         class="w-64 h-64 md:w-72 md:h-72 object-cover rounded-2xl shadow-lg"
                         onerror="this.style.display='none'">
                </div>
            @endif

            <div class="flex-1 flex flex-col justify-center">
                <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">{{ $album->title }}</h1>
                <p class="text-xl text-gray-500 font-light mb-6">{{ $album->artist }}</p>

                <p class="text-sm text-gray-400 mb-6">
                    Добавленно
                    {{ $album->created_at->format('d.m.Y') }}
                    @if($album->updated_at->ne($album->created_at))
                        · Обновлено {{ $album->updated_at->format('d.m.Y') }}
                    @endif
                </p>

                @auth
                    <div class="flex gap-3">
                        <a href="{{ route('albums.edit', $album) }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors">Редактировать</a>
                        <form method="POST" action="{{ route('albums.destroy', $album) }}"
                              onsubmit="return confirm('Удалить этот альбом?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-5 py-2.5 bg-red-900 text-white text-sm font-medium rounded-xl hover:bg-red-800 transition-colors">
                                Удалить
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Description --}}
        @if($album->description)
            <div class="pt-8 border-t border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Об альбоме</h2>
                <div class="text-gray-600 leading-relaxed text-base">
                    {!! $album->description !!}
                </div>
            </div>
        @endif

        {{-- Back Link --}}
        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('albums.index') }}" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">←
                Все альбомы</a>
        </div>
    </div>
@endsection
