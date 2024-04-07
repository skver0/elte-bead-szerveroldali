<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Illuminate\Support\Facades\DB;

class ContestController extends Controller
{
    function show($id)
    {
        $match = Contest::findOrFail($id);
        // store character and enemy seperately in match
        $match->character = $match->characters->where('enemy', false)->first();
        $match->enemy = $match->characters->where('enemy', true)->first();

        // remove public from place->image
        $match->place->image = str_replace('public', '', $match->place->image);

        // add hero_hp and enemy_hp to characters

        $match->character->hp = DB::table('character_contest')
            ->where('character_id', $match->character->id)
            ->where('contest_id', $match->id)
            ->first()->hero_hp;

        $match->enemy->hp = DB::table('character_contest')
            ->where('character_id', $match->enemy->id)
            ->where('contest_id', $match->id)
            ->first()->hero_hp;

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

        if ($character->enemy) {
            abort(403);
        }

        if (auth()->user()->is_admin) {
            abort(403);
        }

        $match = new Contest();
        $match->place_id = $places->random()->id;
        $match->save();

        $enemy = $enemies->random()->id;

        $match->characters()->attach($id, [
            'hero_hp' => 20,
            'enemy_hp' => 20,
            'enemy_id' => $enemy
        ]);

        $match->characters()->attach($enemy, [
            'hero_hp' => 20,
            'enemy_hp' => 20,
            'enemy_id' => $id
        ]);
        return redirect('/match/' . $match->id . '?character_id=' . $id);
    }

    function calculateDamage($attackType, $att, $def)
    {
        /*Sérülés pontszámának kiszámítása:

Készíts egy metódust, ami kiszámítja a sérülés pontszámát a következők szeirnt:

    Paraméterek:
        a támadás típusa (melee, ranged, special)
        a támadó karakter (ATT) adatai (hp, defence, strength, accuracy, magic)
        a védekező karakter (DEF) adatai (hp, defence, strength, accuracy, magic)
    Kimenet: a sérülés pontszáma (float)
    Sérülés kiszámítása:
        Melee: (DEF.HP) - ((ATT.STRENGTH * 0.7 + ATT.ACCURACY * 0.1 + ATT.MAGIC * 0.1) - DEF.DEFENCE)
        Ranged: (DEF.HP) - ((ATT.STRENGTH * 0.1 + ATT.ACCURACY * 0.7 + ATT.MAGIC * 0.1) - DEF.DEFENCE)
        Special (magic): (DEF.HP) - ((ATT.STRENGTH * 0.1 + ATT.ACCURACY * 0.1 + ATT.MAGIC * 0.7) - DEF.DEFENCE)
        Magyarul: a védekező karakter életerejéből vond ki a védekező karakter védekező képességével csökkentett támadó karakter támadó képességének súlyozott összegét.
        Ha a sérülés pontszám negatív lenne (nagyobb a védekező karakter védekező pontszáma (defence), mint a támadó karakter támadásának ereje), akkor 0-át adj vissza!
*/

        $attacker = Character::findOrFail($att);
        $defender = Character::findOrFail($def);

        $damage = 0;

        switch ($attackType) {
            case 'melee':
                $damage = $defender->hp - ((($attacker->strength * 0.7) + ($attacker->accuracy * 0.1) + ($attacker->magic * 0.1)) - $defender->defence);
                break;
            case 'ranged':
                $damage = $defender->hp - ((($attacker->strength * 0.1) + ($attacker->accuracy * 0.7) + ($attacker->magic * 0.1)) - $defender->defence);
                break;
            case 'special':
                $damage = $defender->hp - ((($attacker->strength * 0.1) + ($attacker->accuracy * 0.1) + ($attacker->magic * 0.7)) - $defender->defence);
                break;
        }

        return max(0, $damage);
    }

    function updateHistory($match, $character, $attack, $damage)
    {
        // update character and enemy history in match
        $history = $match->history;
        $history[$character->id][] = $attack . ' attack - ' . $damage . ' damage';

        $match->update([
            'history' => $history
        ]);
    }

    function update($id)
    {
        $match = Contest::findOrFail($id);

        // get attack type
        $attack = request('attack');

        $enemy = Contest::findOrFail($id)->characters->where('enemy', true)->first();
        $character = Contest::findOrFail($id)->characters->where('enemy', false)->first();

        $enemy->hp = DB::table('character_contest')
            ->where('character_id', $enemy->id)
            ->where('contest_id', $id)
            ->first()->hero_hp;

        $character->hp = DB::table('character_contest')
            ->where('character_id', $character->id)
            ->where('contest_id', $id)
            ->first()->hero_hp;

        $damage = $this->calculateDamage($attack, $character->id, $enemy->id);

        $enemyHp = max(0, $enemy->hp - $damage);

        $this->updateHistory($match, $enemy, $attack, $damage);

        $enemyAttack = ['melee', 'ranged', 'special'][rand(0, 2)];
        $enemyDamage = $this->calculateDamage($enemyAttack, $enemy->id, $character->id);

        $characterHp = max(0, $character->hp - $enemyDamage);

        $this->updateHistory($match, $character, $enemyAttack, $enemyDamage);

        // update character hp
        DB::table('character_contest')
            ->where('character_id', $character->id)
            ->where('contest_id', $match->id)
            ->update(['hero_hp' => $characterHp]);

        // update enemy hp
        DB::table('character_contest')
            ->where('character_id', $enemy->id)
            ->where('contest_id', $match->id)
            ->update(['hero_hp' => $enemyHp]);

        if ($enemyHp === 0) {
            $match->update([
                'win' => true,
            ]);

            return redirect('/match/' . $match->id);
        }

        if ($characterHp === 0) {
            $match->update([
                'win' => false,
            ]);

            return redirect('/match/' . $match->id);
        }

        return redirect('/match/' . $match->id);
    }
}
