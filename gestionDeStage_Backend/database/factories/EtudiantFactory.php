<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EtudiantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom_Etud' => fake()->name(),
            'pre_Etud' => fake()->name(),
            'lieuNaiss' => fake()->name(),
            'telEtud' => fake()->numberBetween(0,55555555),
            'numCarte' => fake()->numberBetween(0,55555555),
            'diplome' => fake()->name(),
            'specialite' => fake()->name(),
            'id_Dep' => 1,
            'dateNaiss' => fake()->date(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
