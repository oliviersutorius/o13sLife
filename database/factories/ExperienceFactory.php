<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        $dateDebut = $this->faker->dateTimeBetween('-10 years', '-1 year');

        return [
            'titre_poste' => $this->faker->jobTitle(),
            'entreprise' => $this->faker->company(),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => $this->faker->optional(0.7)->dateTimeBetween($dateDebut, 'now')?->format('Y-m-d'),
            'description' => $this->faker->paragraph(3),
            'technologies' => $this->faker->randomElements(
                ['PHP', 'Laravel', 'Vue.js', 'React', 'MySQL', 'Docker', 'Git', 'TypeScript', 'TailwindCSS'],
                $this->faker->numberBetween(2, 5)
            ),
            'is_published' => false,
        ];
    }

    public function publié(): static
    {
        return $this->state(['is_published' => true]);
    }
}
