<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $password = Hash::make('0000');
        $sex = fake()->randomElement(['homme', 'femme']);
        $role = fake()->randomElement(['directeur', 'gestionaire','formateur']);
        return [
            'nom' => fake()->firstName,
            'prenom' => fake()->lastName,
            'email' => fake()->unique()->safeEmail(),
            'tel'=> fake()->unique()->phoneNumber,
            'sex'=> $sex,
            'role'=>$role,
            'email_verified_at' => now(),
            'password' => $password,
//            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
