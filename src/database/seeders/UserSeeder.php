<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create admin
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.hu',
            'password' => 'admin',
            'is_admin' => true,
        ]);

        // create another admin cuz the first admin needs to fight with someone
        User::factory()->create([
            'name' => 'admin2',
            'email' => 'admin2@admin2.hu',
            'password' => 'admin',
            'is_admin' => true,
        ]);

        User::factory(2)->create();
    }
}
