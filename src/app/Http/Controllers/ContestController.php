<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return redirect('/match/' . $match->id);
    }
}
