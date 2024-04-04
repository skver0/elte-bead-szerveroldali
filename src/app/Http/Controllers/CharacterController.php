<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    function index()
    {
        $characters = Character::query()->where('user_id', auth()->user()->id)->orWhere('enemy', true)->get();

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
    }

    function destroy($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        $character->delete();

        return redirect()->route('dashboard');
    }

    function store()
    {
        $data = request()->validate([
            'name' => 'required|string',
            'defence' => 'required|integer',
            'strength' => 'required|integer',
            'accuracy' => 'required|integer',
            'magic' => 'required|integer',
            'enemy' => 'string'
        ]);

        if (array_sum($data) !== 20) {
            return back()->withErrors([
                'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
            ]);
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
