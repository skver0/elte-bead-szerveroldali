<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    function show($id)
    {
        $match = Contest::findOrFail($id);
        $characters = $match->characters;

        return view('match', [
            'match' => $match,
            'characters' => $characters
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

        // we need to create a character contest
        $match = new Contest();
        $match->place_id = $places->random()->id;
        $match->save();

        // we need to attach the character to the contest in charactercontest table
        $match->characters()->attach($character->id, [
            'hero_hp' => '100',
            'enemy_hp' => '100',
            'enemy_id' => $enemies->random()->id
        ]);

        return redirect('/match/' . $match->id);
    }
}
