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
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        User::factory(2)->create();
    }
}
