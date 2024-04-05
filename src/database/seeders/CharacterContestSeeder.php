<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CharacterContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $characters = Character::all()->where('enemy', true);
        $contests = Contest::all();
        $enemies = Character::where('enemy', true)->get();

        foreach ($characters as $character) {
            foreach ($contests as $contest) {
                foreach ($enemies as $enemy) {
                    $heroHp = rand(0, 100);
                    $enemyHp = rand(0, 100);

                    // the attaching of character to contest
                    $contest->characters()->attach($character->id, [
                        'hero_hp' => $heroHp,
                        'enemy_hp' => $enemyHp,
                        'enemy_id' => $enemy->id
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
    }
}
