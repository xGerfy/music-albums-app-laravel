@extends('layouts.app')

@section('nav-title', $album ? 'Редактирование' : 'Новый альбом')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-10">
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">@if($album)
                    Редактирование
                @else
                    Новый альбом
                @endif</h1>
        </div>

        <form id="albumForm" method="POST"
              action="{{ $album ? route('albums.update', $album) : route('albums.store') }}" class="space-y-6">
            @csrf
            @if($album)
                @method('PUT')
            @endif

            {{-- Title with Autocomplete --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                <div class="flex flex-col gap-3">
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $album->title ?? '') }}"
                           required
                           class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all"
                           placeholder="Введите название альбома">
                    <button type="button"
                            id="autocompleteBtn"
                            class="px-5 py-3 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors whitespace-nowrap cursor-pointer">
                        Искать на Last.fm
                    </button>
                </div>
                @error('title')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                <div id="autocompleteStatus" class="mt-2 text-sm"></div>
            </div>

            {{-- Artist --}}
            <div>
                <label for="artist" class="block text-sm font-medium text-gray-700 mb-2">Исполнитель</label>
                <input type="text"
                       id="artist"
                       name="artist"
                       value="{{ old('artist', $album->artist ?? '') }}"
                       required
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all"
                       placeholder="Имя исполнителя">
                @error('artist')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                <textarea id="description"
                          name="description"
                          rows="5"
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all resize-y"
                          placeholder="Расскажите об альбоме">{{ old('description', $album->description ?? '') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cover Image URL --}}
            <div>
                <label for="cover_image_url" class="block text-sm font-medium text-gray-700 mb-2">Обложка</label>
                <input type="url"
                       id="cover_image_url"
                       name="cover_image_url"
                       value="{{ old('cover_image_url', $album->cover_image_url ?? '') }}"
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all"
                       placeholder="https://example.com/cover.jpg">
                @error('cover_image_url')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                @if($album && $album->cover_image_url)
                    <div class="mt-6">
                        <img src="{{ $album->cover_image_url }}"
                             alt="Preview"
                             class="w-32 h-32 object-cover rounded-xl shadow-sm"
                             onerror="this.style.display='none'">
                    </div>
                @endif
                <div id="coverPreview" class="mt-6"></div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 bg-gray-900 text-white py-3.5 rounded-xl text-base font-medium hover:bg-gray-800 transition-colors cursor-pointer">
                    @if($album)
                        Сохранить
                    @else
                        Создать
                    @endif
                </button>
                <a href="{{ route('albums.index') }}"
                   class="flex-1 text-center bg-red-800 text-white py-3.5 rounded-xl text-base font-medium hover:bg-red-700 transition-colors">Отмена</a>
            </div>
        </form>

        {{-- Delete Button --}}
        @if($album)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <form method="POST" action="{{ route('albums.destroy', $album) }}"
                      onsubmit="return confirm('Удалить этот альбом?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-800 text-white py-3.5 rounded-xl text-base font-medium hover:bg-red-700 transition-colors cursor-pointer">
                        Удалить альбом
                    </button>
                </form>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const autocompleteBtn = document.getElementById('autocompleteBtn');
                const titleInput = document.getElementById('title');
                const artistInput = document.getElementById('artist');
                const descriptionInput = document.getElementById('description');
                const coverImageUrl = document.getElementById('cover_image_url');
                const statusDiv = document.getElementById('autocompleteStatus');
                const coverPreview = document.getElementById('coverPreview');

                autocompleteBtn.addEventListener('click', async function () {
                    const title = titleInput.value.trim();

                    if (!title) {
                        statusDiv.innerHTML = '<span class="text-red-500">Введите название</span>';
                        return;
                    }

                    autocompleteBtn.disabled = true;
                    autocompleteBtn.textContent = '...';
                    statusDiv.innerHTML = '<span class="text-gray-400">Поиск...</span>';

                    try {
                        const response = await fetch('/albums/autocomplete/search', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                    document.querySelector('input[name="_token"]').value
                            },
                            body: JSON.stringify({title: title})
                        });

                        const data = await response.json();

                        if (data.success && data.data) {
                            if (data.data.artist) artistInput.value = data.data.artist;
                            if (data.data.description) descriptionInput.value = data.data.description;
                            if (data.data.cover_image_url) {
                                coverImageUrl.value = data.data.cover_image_url;
                                coverPreview.innerHTML = `<img src="${data.data.cover_image_url}" class="w-32 h-32 object-cover rounded-xl shadow-sm" onerror="this.style.display='none'">`;
                            }
                            statusDiv.innerHTML = '<span class="text-green-600">Заполнено из Last.fm</span>';
                        } else {
                            statusDiv.innerHTML = '<span class="text-red-500">' + (data.message || 'Не найдено') + '</span>';
                        }
                    } catch (error) {
                        statusDiv.innerHTML = '<span class="text-red-500">Ошибка</span>';
                    } finally {
                        autocompleteBtn.disabled = false;
                        autocompleteBtn.textContent = 'Искать на Last.fm';
                    }
                });

                coverImageUrl.addEventListener('input', function () {
                    const url = this.value.trim();
                    coverPreview.innerHTML = url ? `<img src="${url}" class="w-32 h-32 object-cover rounded-xl shadow-sm" onerror="this.style.display='none'">` : '';
                });
            });
        </script>
    @endpush
@endsection
