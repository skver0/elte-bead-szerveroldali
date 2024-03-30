<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        // make sure that each user has at least one character
        $users->each(function (User $user) {
            Character::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        // create additional characters that are not associated with any user
        // so called "enemies"
        Character::factory(2)->create([
            'user_id' => null,
            'enemy' => true
        ]);
    }
}
