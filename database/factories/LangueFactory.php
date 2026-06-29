<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Langue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Langue>
 */
class LangueFactory extends Factory
{
    public function definition(): array
    {
        $langues = [
            ['langue' => 'Français', 'niveau' => 'Natif'],
            ['langue' => 'Anglais', 'niveau' => 'Professionnel (C1)'],
            ['langue' => 'Espagnol', 'niveau' => 'Intermédiaire (B2)'],
            ['langue' => 'Italien', 'niveau' => 'Notions (A2)'],
            ['langue' => 'Allemand', 'niveau' => 'Débutant (A1)'],
        ];

        $choice = $this->faker->randomElement($langues);

        return [
            'langue' => $choice['langue'],
            'niveau' => $choice['niveau'],
            'is_published' => false,
        ];
    }

    public function publié(): static
    {
        return $this->state(['is_published' => true]);
    }
}
