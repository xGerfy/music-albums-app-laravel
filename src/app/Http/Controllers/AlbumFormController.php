<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumFormController extends Controller
{
    public function edit(?string $albumId = null)
    {
        if (!Auth::check()) {
            abort(403, 'Необходима авторизация для редактирования');
        }

        $album = null;
        if ($albumId && $albumId !== 'new') {
            $album = Album::findOrFail($albumId);
        }

        return view('albums.form', compact('album'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Необходима авторизация');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url'],
        ]);

        $validated['user_id'] = Auth::id();

        $album = Album::create($validated);

        return redirect()->route('albums.index')
            ->with('success', 'Альбом успешно создан');
    }

    public function update(Request $request, Album $album)
    {
        if (!Auth::check()) {
            abort(403, 'Необходима авторизация');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url'],
        ]);

        $album->update($validated);

        return redirect()->route('albums.show', $album)
            ->with('success', 'Альбом успешно обновлен');
    }
}
