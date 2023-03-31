<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */

    protected $article = Article::class;

    public function definition(): array
    {
        $categorie_code = Categorie::pluck('code')->toArray();

        return [
            'categorie_code' => $this->faker->randomElement($categorie_code),
            'designtion' => $this->faker->unique->randomElement([
                "Laisse pour chien",
                "Nourriture pour chat",
                "Aquarium en verre",
                "Panier pour chien",
                "Tapis de voiture",
                "Huile moteur",
                "Liquide de frein",
                "Autoradio",
                "Soin pour les cheveux",
                "Parfum pour femme",
                "Eau de toilette pour homme",
                "Rouge à lèvres",
                "Mascara",
                "Vernis à ongles",
                "Crayon pour les yeux",
            ]),
            'prix' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
