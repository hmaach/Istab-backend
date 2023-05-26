<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::query()
                    ->where('role', '=', 'stagiaire')
                    ->get()->random()->id;
            },
            'titre' => $this->faker->text(30),
            'dateDeb' => $this->faker->date(),
            'dateFin' => $this->faker->date(),
            'place' => $this->faker->address, // Faker-generated place
            'missions' => json_encode([
                $this->faker->sentence,
                $this->faker->sentence,
                $this->faker->sentence,
            ]) // Faker-generated missions
        ];
    }
}

