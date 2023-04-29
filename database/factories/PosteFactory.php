<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PosteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['announce', 'cour','exercice','note']);
        $audience = fake()->randomElement(['tous', 'filiere','groupe']);
        $id_user = fake()->numberBetween(1,10);
        return [
            'id_user'=>$id_user,
            'libelle' => fake()->text(100),
            'type' => $type,
            'audience' => $audience,
        ];
    }
}
