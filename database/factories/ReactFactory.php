<?php

namespace Database\Factories;

use App\Models\Poste;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\React>
 */
class ReactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_poste' => fake()->unique()->numberBetween(1, 25),
            'id_user' => fake()->unique()->numberBetween(1, 99),
        ];
    }

}
