<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LastFmService
{
    protected string $apiKey;
    protected string $baseUrl = 'http://ws.audioscrobbler.com/2.0/';

    public function __construct()
    {
        $this->apiKey = config('services.lastfm.key');
    }

    /**
     * Поиск альбома и получение информации
     */
    public function searchAlbum(string $albumName): ?array
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $cacheKey = 'lastfm_search_' . md5($albumName);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($albumName) {
            try {
                // Первый запрос - поиск альбома
                $searchResponse = Http::timeout(10)->retry(2, 1000)->get($this->baseUrl, [
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

                if (!isset($searchData['results']['albummatches']['album'][0])) {
                    return null;
                }

                $albumData = $searchData['results']['albummatches']['album'][0];
                $artist = $albumData['artist'];
                $title = $albumData['name'];

                // Второй запрос - получение описания (с отдельным кэшем)
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

    /**
     * Получение описания альбома (отдельный запрос с кэшем)
     */
    protected function getAlbumDescription(string $artist, string $albumName): ?string
    {
        $cacheKey = 'lastfm_desc_' . md5($artist . '_' . $albumName);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($artist, $albumName) {
            try {
                $response = Http::timeout(10)->retry(2, 1000)->get($this->baseUrl, [
                    'method' => 'album.getInfo',
                    'artist' => $artist,
                    'album' => $albumName,
                    'api_key' => $this->apiKey,
                    'format' => 'json',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['album']['wiki']['summary'] ?? null;
                }
            } catch (\Exception $e) {
                return null;
            }

            return null;
        });
    }

    /**
     * Получение самого большого изображения из списка
     */
    protected function getLargestImage(array $images): ?string
    {
        if (empty($images)) {
            return null;
        }

        // Last.fm возвращает изображения в порядке возрастания размера
        $lastImage = end($images);
        return $lastImage['#text'] ?? null;
    }
}
