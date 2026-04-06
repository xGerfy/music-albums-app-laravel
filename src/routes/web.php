<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AlbumFormController;
use App\Http\Controllers\AlbumAutocompleteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Главная страница - перенаправление на список альбомов
Route::get('/', function () {
    return redirect()->route('albums.index');
});

// Аутентификация
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Альбомы (публичный доступ к просмотру)
Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/albums/{album}', [AlbumController::class, 'show'])->name('albums.show');

// Альбомы (требуется авторизация)
Route::middleware('auth')->group(function () {
    Route::get('/albums/edit/{album?}', [AlbumFormController::class, 'edit'])->name('albums.edit');
    Route::post('/albums', [AlbumFormController::class, 'store'])->name('albums.store');
    Route::put('/albums/{album}', [AlbumFormController::class, 'update'])->name('albums.update');
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');

    // Автозаполнение из Last.fm
    Route::post('/albums/autocomplete/search', [AlbumAutocompleteController::class, 'search'])->name('albums.autocomplete.search');
});
