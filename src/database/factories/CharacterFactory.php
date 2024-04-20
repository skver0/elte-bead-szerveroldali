<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $defence = fake()->numberBetween(0, 3);
        $strength = fake()->numberBetween(0, 20 - $defence);
        $accuracy = fake()->numberBetween(0, 20 - $defence - $strength);
        $magic = 20 - $defence - $strength - $accuracy;

        return [
            'name' => fake()->name,
            'enemy' => false,
            'defence' => $defence,
            'strength' => $strength,
            'accuracy' => $accuracy,
            'magic' => $magic,
        ];
    }
}
