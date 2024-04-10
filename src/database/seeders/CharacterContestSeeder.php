<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CharacterContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contests = Contest::all();

        foreach ($contests as $contest) {
            $character = Character::all()->where('enemy', false)->random();
            $enemy = Character::all()->where('enemy', true)->where('id', '!=', $character->id)->random();
            $heroHp = rand(0, 20);
            $enemyHp = rand(0, 20);

            $contest->characters()->attach($character->id, [
                'enemy_id' => $enemy->id,
                'hero_hp' => $heroHp,
                'enemy_hp' => $enemyHp,
            ]);

            // update contest "win" field if enemy hp is 0
            $contest->update([
                'win' => $enemyHp === 0,
            ]);
        }
    }
}
