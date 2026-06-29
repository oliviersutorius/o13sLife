<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CentreInteret;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CentreInteret>
 */
class CentreInteretFactory extends Factory
{
    public function definition(): array
    {
        $centres = [
            'Photographie', 'Randonnée', 'Lecture', 'Musique', 'Voyage',
            'Cuisine', 'Cyclisme', 'Yoga', 'Open source', 'Jeux de société',
            'Astronomie', 'Cinéma', 'Jardinage', 'Escalade', 'Natation',
        ];

        return [
            'libelle' => $this->faker->randomElement($centres),
            'is_published' => false,
        ];
    }

    public function publié(): static
    {
        return $this->state(['is_published' => true]);
    }
}
