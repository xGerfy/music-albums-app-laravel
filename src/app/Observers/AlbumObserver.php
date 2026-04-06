<?php

namespace App\Observers;

use App\Models\Album;
use App\Models\AlbumLog;
use Illuminate\Support\Facades\Auth;

class AlbumObserver
{
    public function created(Album $album): void
    {
        AlbumLog::create([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'new_values' => $album->toArray(),
        ]);
    }

    public function updated(Album $album): void
    {
        $changes = $album->getChanges();
        $original = $album->getOriginal();

        $oldValues = array_intersect_key($original, array_flip(array_keys($changes)));
        $newValues = $changes;

        AlbumLog::create([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    public function deleted(Album $album): void
    {
        AlbumLog::create([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'old_values' => $album->toArray(),
        ]);
    }

    public function restored(Album $album): void
    {
        //
    }

    public function forceDeleted(Album $album): void
    {
        //
    }
}
