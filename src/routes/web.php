<?php

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

Route::get('/dashboard', function () {
    // get user's characters
    $characters = auth()->user()->characters;

    return view('dashboard', [
        'characters' => $characters
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/character/{id}', function ($id) {
    $character = Character::findOrFail($id);
    $matches = $character->matches;


    return view('character', [
        'character' => $character,
        'matches' => $matches
    ]);
})->middleware(['auth', 'verified'])->name('character');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
