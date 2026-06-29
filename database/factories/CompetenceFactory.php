<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Competence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competence>
 */
class CompetenceFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Backend', 'Frontend', 'DevOps', 'Base de données', 'Mobile', 'Outils'];

        return [
            'categorie' => $this->faker->randomElement($categories),
            'nom' => $this->faker->randomElement([
                'PHP', 'Laravel', 'Python', 'JavaScript', 'TypeScript',
                'Vue.js', 'React', 'MySQL', 'PostgreSQL', 'Docker',
                'Git', 'Linux', 'Redis', 'TailwindCSS', 'AWS',
            ]),
            'niveau' => $this->faker->randomElement(['debutant', 'intermediaire', 'expert']),
            'is_published' => false,
        ];
    }

    public function publié(): static
    {
        return $this->state(['is_published' => true]);
    }
}
