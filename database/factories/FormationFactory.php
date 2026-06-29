<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Formation>
 */
class FormationFactory extends Factory
{
    public function definition(): array
    {
        $diplomes = [
            'Master Informatique',
            'Licence Informatique',
            'BTS SIO',
            'DUT Informatique',
            'Ingénieur Informatique',
            'Master MIAGE',
            'Bachelor Développement Web',
        ];

        $ecoles = [
            'Université Paris-Saclay',
            'Université Lyon 1',
            'EPITECH',
            'IUT de Bordeaux',
            'INSA Lyon',
            'Université de Strasbourg',
        ];

        return [
            'ecole' => $this->faker->randomElement($ecoles),
            'annee' => $this->faker->numberBetween(2000, 2024),
            'diplome' => $this->faker->randomElement($diplomes),
            'is_published' => false,
        ];
    }

    public function publié(): static
    {
        return $this->state(['is_published' => true]);
    }
}
