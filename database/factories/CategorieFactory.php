<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categorie>
 */
class CategorieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $categorie = Categorie::class;
    public function definition(): array
    {
        return [
            'intitule' => $this->faker->unique()->randomElement(["Animaux", "Automobiles", "Beauté et bien-être", "Électronique", "Immobilier", "Informatique", "Musique et instruments", "Santé et médical", "Sports et loisirs", "Téléphonie et communication",
            ]),
        ];
    }
}
