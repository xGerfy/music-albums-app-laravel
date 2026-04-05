<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{

    public function index(request $request)
    {

        $query = Album::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('artist', 'ilike', "%{$search}%");
            });
        }

        $albums = $query->latest()->paginate(9)->withQueryString();

        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return redirect()->route('albums.edit', ['album' => 'new']);
    }

    public function show(Album $album)
    {
        return view('albums.show', compact('album'));
    }

    public function destroy(Album $album)
    {
        if (!auth()->check()) {
            abort(403, 'Необходима авторизация');
        }

        $album->delete();

        return redirect()->route('albums.index')
            ->with('success', 'Альбом успешно удален');
    }
}
