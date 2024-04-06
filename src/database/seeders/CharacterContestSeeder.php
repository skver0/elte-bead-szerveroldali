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
            $heroHp = rand(0, 100);
            $enemyHp = rand(0, 100);

            $contest->characters()->attach($character->id, [
                'hero_hp' => $heroHp,
                'enemy_hp' => $enemyHp,
                'enemy_id' => $enemy->id
            ]);

            $contest->characters()->attach($enemy->id, [
                'hero_hp' => $enemyHp,
                'enemy_hp' => $heroHp,
                'enemy_id' => $character->id
            ]);

            // update contest "win" field if enemy hp is 0
            if ($enemyHp === 0) {
                $contest->update([
                    'win' => true,
                ]);
            }
        }
    }
}
