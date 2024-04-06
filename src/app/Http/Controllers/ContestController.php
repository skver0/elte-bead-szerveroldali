<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;

class ContestController extends Controller
{
    function show($id)
    {
        $match = Contest::findOrFail($id);
        // store character and enemy seperately in match
        $match->character = $match->characters->where('enemy', false)->first();
        $match->enemy = $match->characters->where('enemy', true)->first();

        return view('match', [
            'match' => $match
        ]);
    }

    function store()
    {
        $places = Place::all();
        $enemies = Character::where('enemy', true)->where('id', '!=', request('character_id'))->get();

        $id = request('character_id');

        $character = Character::findOrFail($id);
        if (!$character) {
            abort(404);
        }

        $match = new Contest();
        $match->place_id = $places->random()->id;
        $match->save();

        $enemy = $enemies->random()->id;

        $match->characters()->attach($id, [
            'hero_hp' => 100,
            'enemy_hp' => 100,
            'enemy_id' => $enemy
        ]);

        $match->characters()->attach($enemy, [
            'hero_hp' => 100,
            'enemy_hp' => 100,
            'enemy_id' => $id
        ]);

        return redirect('/match/' . $match->id . '?character_id=' . $id);
    }
}
