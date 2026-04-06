@extends('layouts.app')

@section('nav-title', 'Альбомы')

@section('content')
    {{-- Header --}}
    <div class="mb-12">
        <h1 class="text-4xl md:text-5xl font-semibold text-gray-900 tracking-tight mb-3">Музыкальные альбомы</h1>
        <p class="text-lg text-gray-500 font-light">Коллекция лучших пластинок</p>
    </div>

    {{-- Search and Add --}}
    <div class="mb-10 space-y-4">
        <form method="GET" action="{{ route('albums.index') }}" class="flex gap-3">
            <input type="text"
                   name="search"
                   placeholder="Поиск"
                   value="{{ request('search') }}"
                   class="flex-1 min-w-0 px-4 py-3 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all placeholder:text-gray-400">
            <button type="submit"
                    class="shrink-0 px-6 py-3 bg-gray-900 text-white text-base font-medium rounded-xl hover:bg-gray-800 transition-colors cursor-pointer">
                Искать
            </button>
        </form>

        @auth
            <a href="{{ route('albums.edit', ['album' => 'new']) }}"
               class="block w-full px-6 py-3 bg-gray-900 text-white text-base font-medium rounded-xl hover:bg-gray-800 transition-colors text-center">Добавить
                альбом</a>
        @endauth
    </div>

    {{-- Empty State --}}
    @if($albums->isEmpty())
        <div class="text-center py-20">
            <p class="text-xl text-gray-400 font-light">Альбомы не найдены =(</p>
            @if(request('search'))
                <a href="{{ route('albums.index') }}" class="inline-block mt-6 text-gray-900 hover:underline text-sm">←
                    Назад</a>
            @elseif(auth()->check())
                <p class="mt-3"><a href="{{ route('albums.edit', ['album' => 'new']) }}"
                                   class="text-gray-900 hover:underline">Добавьте первый альбом</a></p>
            @endif
        </div>
    @else
        {{-- Albums Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-10">
            @foreach($albums as $album)
                <a href="{{ route('albums.show', $album) }}" class="group block">
                    {{-- Cover Image --}}
                    <div
                        class="aspect-square rounded-2xl overflow-hidden bg-gray-200 mb-4 shadow-sm group-hover:shadow-lg transition-shadow duration-300">
                        @if($album->cover_image_url)
                            <img src="{{ $album->cover_image_url }}"
                                 alt="{{ $album->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.style.display='none'">
                        @endif
                    </div>

                    {{-- Album Info --}}
                    <div>
                        <h3 class="text-base font-medium text-gray-900 group-hover:text-gray-600 transition-colors truncate">{{ $album->title }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $album->artist }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-16">
            {{ $albums->links('vendor.pagination.simple-custom') }}
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('keydown', function (e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (e.ctrlKey || e.metaKey || e.altKey) return;

            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                // Перемещаем курсор в конец строки
                const len = searchInput.value.length;
                searchInput.setSelectionRange(len, len);
            }
        });
    </script>
@endpush
