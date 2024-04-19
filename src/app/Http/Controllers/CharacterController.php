<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    function index()
    {
        if (auth()->user()->is_admin)
            $characters = Character::query()->where('user_id', auth()->user()->id)->orWhere('enemy', true)->get();
        else
            $characters = Character::query()->where('user_id', auth()->user()->id)->get();

        return view('dashboard', [
            'characters' => $characters
        ]);
    }

    function create()
    {
        return view('character-create');
    }

    function show($id)
    {
        $character = Character::findOrFail($id);
        $matches = $character->contests;

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($matches as $match) {
            $match->enemy = Character::findOrFail($match->characters->map(function ($character) {
                return $character->pivot->enemy_id;
            })->first());
        }

        return view('character', [
            'character' => $character,
            'matches' => $matches
        ]);
    }

    function edit($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        return view('character-edit', [
            'character' => $character
        ]);
    }

    function update($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        $character->update(request()->validate([
            'name' => ['string', 'required'],
            'defence' => ['integer', 'required', 'max:3'],
            'strength' => ['integer', 'required'],
            'accuracy' => ['integer', 'required'],
            'magic' => ['integer', 'required'],
            'enemy' => 'string'
        ]));

        if (array_sum(request()->all()) !== 20) {
            // return with error but keep data in the input fields
            return back()->withErrors([
                'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
            ])->withInput();
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
    }

    function destroy($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user or the user is an admin
        if ($character->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        if (!$character->enemy && auth()->user()->is_admin) {
            abort(403);
        }

        $character->delete();

        return redirect()->route('dashboard');
    }

    function store()
    {
        $data = request()->validate([
            'name' => ['string', 'required'],
            'defence' => ['required', 'integer', 'max:3'],
            'strength' => ['required', 'integer'],
            'accuracy' => ['required', 'integer'],
            'magic' => ['required', 'integer'],
            'enemy' => 'string'
        ]);

        if (array_sum($data) !== 20) {
            return back()->withErrors([
                'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
            ])->withInput();
        }

        if (!isset($data['enemy'])) {
            $data['enemy'] = false;
        } else {
            if (auth()->user()->is_admin) {
                if (isset($data['enemy']))
                    $data['enemy'] = true;
            }
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
    }
}
