<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use Illuminate\Database\Seeder;

class CharacterContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $characters = Character::all()->where('enemy', true);
        $contests = Contest::all();

        foreach ($characters as $character) {
            foreach ($contests as $contest) {
                $heroHp = rand(0, 100);
                $enemyHp = rand(0, 100);

                $character->matches()->attach(
                    $contest->id,
                    [
                        'hero_hp' => $heroHp,
                        'enemy_hp' => $enemyHp,
                    ]
                );

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