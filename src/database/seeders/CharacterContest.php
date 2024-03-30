<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterContest extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $characters = Character::all();
        $contests = Contest::all();

        foreach ($characters as $character) {
            foreach ($contests as $contest) {
                $character->matches()->attach(
                    $contest->id,
                    [
                        'hero_hp' => rand(50, 100),
                        'enemy_hp' => rand(50, 100),
                    ]
                );
            }
        }
    }
}
