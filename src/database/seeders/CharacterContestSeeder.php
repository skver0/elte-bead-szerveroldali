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
            $user_id = $contest->user()->first()->id;
            $character = Character::all()->where('user_id', $user_id)->random();
            $enemy = Character::all()->where('enemy', true)->where('user_id', '!=', $user_id)->random();
            $heroHp = rand(0, 20);
            $enemyHp = rand(0, 20);

            $contest->characters()->attach($character->id, [
                'enemy_id' => $enemy->id,
                'hero_hp' => $heroHp,
                'enemy_hp' => $enemyHp,
            ]);

            // update contest "win" field if enemy hp is 0
            // otherwise check if hero hp is 0 and update "win" field to false
            if ($enemyHp === 0) {
                $contest->update(['win' => true]);
            } elseif ($heroHp === 0) {
                $contest->update(['win' => false]);
            }
        }
    }
}
