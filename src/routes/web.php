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
    $characters = Character::where('user_id', auth()->id())->get();

    return view('dashboard', [
        'characters' => $characters
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/character/{id}', function ($id) {
    $character = Character::findOrFail($id);
    $matches = $character->matches;

    // check if the character belongs to the user
    if ($character->user_id !== auth()->id()) {
        abort(403);
    }

    // add enemy character to the matches
    foreach ($matches as $match) {
        $match->enemy = $match->characters->where('id', '!=', $character->id)->first();
    }

    return view('character', [
        'character' => $character,
        'matches' => $matches
    ]);
})->middleware(['auth', 'verified'])->name('character');


Route::get('/character/{id}/edit', function ($id) {
    $character = Character::findOrFail($id);

    // check if the character belongs to the user
    if ($character->user_id !== auth()->id()) {
        abort(403);
    }

    return view('character-edit', [
        'character' => $character
    ]);
})->middleware(['auth', 'verified'])->name('character.edit');

Route::patch('/character/{id}', function ($id) {
    $character = Character::findOrFail($id);

    // check if the character belongs to the user
    if ($character->user_id !== auth()->id()) {
        abort(403);
    }

    $character->update(request()->validate([
        'name' => 'required|string',
        'defence' => 'required|integer',
        'strength' => 'required|integer',
        'accuracy' => 'required|integer',
        'magic' => 'required|integer',
        'enemy' => 'string'
    ]));

    if (array_sum(request()->all()) !== 20) {
        return back()->withErrors([
            'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
        ]);
    }

    if (!isset(request()->all()['enemy'])) {
        $character->enemy = false;
    } else {
        if (auth()->user()->is_admin) {
            if (isset(request()->all()['enemy']))
                $character->enemy = true;
        }
    }

    $character->update(request()->all());

    return redirect()->route('character', $character->id);
})->middleware(['auth', 'verified'])->name('character.update');

Route::delete('/character/{id}', function ($id) {
    $character = Character::findOrFail($id);

    // check if the character belongs to the user
    if ($character->user_id !== auth()->id()) {
        abort(403);
    }

    $character->delete();

    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('character.destroy');

Route::get('/match/{id}', function ($id) {
    $match = Contest::findOrFail($id);
    $characters = $match->characters;

    return view('match', [
        'match' => $match,
        'characters' => $characters
    ]);
})->middleware(['auth', 'verified'])->name('match');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// cant really do /character/create because of {id} route above
Route::get('/character-create', function () {
    return view('character-create');
})->middleware(['auth', 'verified'])->name('character.create');

Route::post('/character.store', function () {
    $data = request()->validate([
        'name' => 'required|string',
        'defence' => 'required|integer',
        'strength' => 'required|integer',
        'accuracy' => 'required|integer',
        'magic' => 'required|integer',
        'enemy' => 'string' // idk man, why the hell does the blade return "yes" instead of true ??
    ]);

    if ($data['defence'] + $data['strength'] + $data['accuracy'] + $data['magic'] !== 20) {
        return back()->withErrors([
            'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
        ]);
    }

    if (!isset($data['enemy'])) {
        $data['enemy'] = false;
    } else {
        if (auth()->user()->is_admin)
            $data['enemy'] = true;
    }

    Character::create([
        'name' => $data['name'],
        'defence' => $data['defence'],
        'strength' => $data['strength'],
        'accuracy' => $data['accuracy'],
        'magic' => $data['magic'],
        'enemy' => $data['enemy'],
        'user_id' => auth()->id()
    ]);

    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('character.store');
