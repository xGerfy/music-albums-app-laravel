<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LastFmService
{
    protected string $apiKey;
    protected string $baseUrl = 'http://ws.audioscrobbler.com/2.0/';

    public function __construct(string $apiKey)
    {
        $this->apiKey = config('services.lastfm.key');
    }

    public function searchAlbum(string $albumName): ?array
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $cacheKey = 'lastfm_search_' . md5($albumName);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($albumName) {
            try {
                $searchResponse = Http::timeout(10)->retry(2,1000)->get($this->baseUrl, [
                    'method' => 'album.search',
                    'album' => $albumName,
                    'api_key' => $this->apiKey,
                    'format' => 'json',
                    'limit' => 1,
                ]);

                if (!$searchResponse->successful()) {
                    return null;
                }

                $searchData = $searchResponse->json();

                if (!isset($searchData['results']['albummathes']['album'][0])) {
                    return null;
                }

                $albumData = $searchData['results']['albummathes']['album'][0];
                $artist = $albumData['artists'];
                $title = $albumData['name'];

                $description = $this->getAlbumDescription($artist, $title);

                return [
                    'artist' => $artist,
                    'title' => $title,
                    'description' => $description,
                    'cover_image_url' => $this->getLargestImage($albumData['image'] ?? []),
                ];
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    protected function getAlbumDescription(string $artist, string $albumName): ?string
    {
        $cacheKey = 'lastfm_desc_' . md5($artist . '_' . $albumName);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($artist, $albumName) {
            try {
                $response = Http::timeout(10)->retry(2,1000)->get($this->baseUrl, [
                    'method' => 'album.getInfo',
                    'artist' => $artist,
                    'album' => $albumName,
                    'api_key' => $this->apiKey,
                    'format' => 'json',
                ]);
            } catch (\Exception $e) {
                return null;
            }

            return null;
        });
    }

    protected function getLargestImage(array $images): ?string
    {
        if (empty($images)) {
            return null;
        }

        $lastImage = end($images);
        return $lastImage['#text'] ?? null;
    }
}
