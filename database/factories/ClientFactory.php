<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(["client", "fournisseur"]);
        $categorie = ($type === 'client') ? $this->faker->randomElement(['grand compte', 'particulier', 'revendeur']) : null;

        return [
            'raison_social' => $this->faker->company(),
            'ice' => $this->faker->unique()->randomNumber(8),
            'rc' => $this->faker->unique()->randomNumber(8),
            'type' => $type,
            'categorie' => $categorie,
        ];
    }
}
