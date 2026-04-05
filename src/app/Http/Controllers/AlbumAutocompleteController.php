<?php

namespace App\Http\Controllers;

use App\Services\LastFmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumAutocompleteController extends Controller
{
    protected LastFmService $lastFmService;

    public function __construct(LastFmService $lastFmService)
    {
        $this->lastFmService = $lastFmService;
    }

    public function search(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Необходима авторизация'], 403);
        }

        $request->validate([
            'title' => ['required', 'string', 'min:2'],
        ]);

        $albumInfo = $this->lastFmService->searchAlbum($request->title);

        if ($albumInfo) {
            return response()->json([
                'success' => true,
                'data' => $albumInfo,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Информация об альбоме не найдена',
        ]);
    }
}
