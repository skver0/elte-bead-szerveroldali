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
        $admin = $users->firstWhere('is_admin', true);
        $admin2 = $users->where('is_admin', true)->last();

        // make sure that each user has at least one character
        $users->each(function (User $user) {
            if ($user->is_admin) {
                return;
            }

            Character::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        // create additional characters
        // so called "enemies"
        Character::factory(2)->create([
            'user_id' => $admin->id,
            'enemy' => true
        ]);

        // we need enemies for the second admin as well
        // we cant fight our own characters .-.
        Character::factory(2)->create([
            'user_id' => $admin2->id,
            'enemy' => true
        ]);
    }
}
