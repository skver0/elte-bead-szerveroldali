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
        $defence = $this->faker->numberBetween(0, 20);
        $strength = $this->faker->numberBetween(0, 20 - $defence);
        $accuracy = $this->faker->numberBetween(0, 20 - $defence - $strength);
        $magic = 20 - $defence - $strength - $accuracy;

        return [
            'name' => $this->faker->name,
            'enemy' => false,
            'defence' => $defence,
            'strength' => $strength,
            'accuracy' => $accuracy,
            'magic' => $magic,
        ];
    }
}
