<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Illuminate\Support\Facades\Storage;

class ContestController extends Controller
{
    function show($id)
    {
        $match = Contest::findOrFail($id);
        // use join table to get character and enemy, dont use enemy = true/false

        $match->enemy = Character::findOrFail($match->characters->map(function ($character) {
            return $character->pivot->enemy_id;
        })->first());

        $match->character = Character::findOrFail($match->characters->map(function ($character) {
            return $character->pivot->character_id;
        })->first());

        // add hero_hp and enemy_hp to characters
        $match->character->hp = $match->characters->where('id', $match->character->id)->first()->pivot->hero_hp;
        $match->enemy->hp = $match->characters->where('id', $match->character->id)->first()->pivot->enemy_hp;
        // this is disgusting ^

        // update image path of place
        $match->place->image = Storage::url($match->place->image);

        // add history to character and enemy
        $match->character->history = $match->history[$match->character->id] ?? [];
        $match->enemy->history = $match->history[$match->enemy->id] ?? [];

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

        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        $match = new Contest();
        $match->place_id = $places->random()->id;
        $match->save();

        $enemy = $enemies->random()->id;

        $match->characters()->attach($id, [
            'enemy_id' => $enemy,
            'hero_hp' => 20,
            'enemy_hp' => 20,
        ]);
        return redirect('/match/' . $match->id);
    }

    function calculateDamage($attackType, $att, $def)
    {
        $damage = 0;

        switch ($attackType) {
            case 'melee':
                $damage =  ((($att->strength * 0.7) + ($att->accuracy * 0.1) + ($att->magic * 0.1)) - $def->defence);
                break;
            case 'ranged':
                $damage = ((($att->strength * 0.1) + ($att->accuracy * 0.7) + ($att->magic * 0.1)) - $def->defence);
                break;
            case 'special':
                $damage = ((($att->strength * 0.1) + ($att->accuracy * 0.1) + ($att->magic * 0.7)) - $def->defence);
                break;
        }
        if ($damage < 0)
            return 0;
        return $damage;
    }

    function updateHistory($match, $character, $enemy, $attack, $damage)
    {
        $history = $match->history;
        // update character and enemy history in match
        $history[$character->id][] = 'You attacked with ' . $attack . ' and dealt ' . $damage . ' damage';
        $history[$enemy->id][] = 'Enemy attacked with ' . $attack . ' and dealt ' . $damage . ' damage';
        $match->update([
            'history' => $history
        ]);
    }

    function update($id)
    {
        $match = Contest::findOrFail($id);

        // get attack type
        $attack = request('attack');

        // get enemy from character_contest join table with pivot "enemy_id"
        $enemy = Character::findOrFail($match->characters->map(function ($character) {
            return $character->pivot->enemy_id;
        })->first());

        $character = Character::findOrFail($match->characters->map(function ($character) {
            return $character->pivot->character_id;
        })->first());

        $character->hp = $match->characters->where('id', $character->id)->first()->pivot->hero_hp;
        $enemy->hp = $match->characters->where('id', $character->id)->first()->pivot->enemy_hp;

        $damage = $this->calculateDamage($attack, $character, $enemy);

        $enemyHp = max(0, $enemy->hp - $damage);

        $this->updateHistory($match, $character, $enemy, $attack, $damage);

        $match->characters()->updateExistingPivot($character->id, [
            'enemy_hp' => $enemyHp
        ]);

        if ($enemyHp === 0) {
            $match->update([
                'win' => true
            ]);
            return redirect('/match/' . $match->id);
        }

        $enemyAttack = ["melee", "ranged", "special"][rand(0, 2)];
        $enemyDmg = $this->calculateDamage($enemyAttack, $enemy, $character);

        $characterHp = max(0, $character->hp - $enemyDmg);

        $this->updateHistory($match, $enemy, $character, $enemyAttack, $enemyDmg);

        $match->characters()->updateExistingPivot($character->id, [
            'hero_hp' => $characterHp
        ]);

        // set win if enemy hp is 0
        if ($characterHp === 0) {
            $match->update([
                'win' => false
            ]);
            return redirect('/match/' . $match->id);
        }

        return redirect('/match/' . $match->id);
    }
}
