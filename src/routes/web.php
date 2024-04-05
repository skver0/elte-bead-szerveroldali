<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ProfileController;
use App\Models\Character;
use App\Models\Contest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $characters = Character::count();
    $matches = Contest::count();
    return view('index', [
        'characters' => $characters,
        'matches' => $matches,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', [CharacterController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/character/create', [CharacterController::class, 'create'])->middleware(['auth', 'verified'])->name('character.create');
Route::get('/character/{id}', [CharacterController::class, 'show'])->middleware(['auth', 'verified'])->name('character');
Route::get('/character/{id}/edit', [CharacterController::class, 'edit'])->middleware(['auth', 'verified'])->name('character.edit');
Route::patch('/character/{id}', [CharacterController::class, 'update'])->middleware(['auth', 'verified'])->name('character.update');
Route::delete('/character/{id}', [CharacterController::class, 'destroy'])->middleware(['auth', 'verified'])->name('character.destroy');
Route::post('/character.store', [CharacterController::class, 'store'])->middleware(['auth', 'verified'])->name('character.store');


Route::post('/match/create', [ContestController::class, 'store'])->middleware(['auth', 'verified'])->name('match.store');
Route::get('/match/{id}', [ContestController::class, 'show'])->middleware(['auth', 'verified'])->name('match');

Route::get('/places', [PlaceController::class, 'index'])->middleware(['auth', 'verified'])->name('places');
Route::get('/places/create', [PlaceController::class, 'create'])->middleware(['auth', 'verified'])->name('places.create');
Route::post('/places.store', [PlaceController::class, 'store'])->middleware(['auth', 'verified'])->name('places.store');
Route::get('/places/{id}/edit', [PlaceController::class, 'edit'])->middleware(['auth', 'verified'])->name('places.edit');
Route::patch('/places/{id}', [PlaceController::class, 'update'])->middleware(['auth', 'verified'])->name('places.update');
Route::delete('/places/{id}', [PlaceController::class, 'destroy'])->middleware(['auth', 'verified'])->name('places.destroy');
