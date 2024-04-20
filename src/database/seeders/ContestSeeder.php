<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $places = Place::all();
        $places->each(function (Place $place) {
            $users = User::all();
            Contest::factory()->create([
                'place_id' => $place->id,
                'user_id' => $users->random()->id,
            ]);
        });
    }
}
