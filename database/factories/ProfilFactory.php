<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profil>
 */
class ProfilFactory extends Factory
{
    public function definition(): array
    {
        return [
            'photo' => null,
            'titre' => $this->faker->jobTitle(),
            'email' => $this->faker->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'localisation' => $this->faker->city().', '.$this->faker->country(),
            'lien_linkedin' => 'https://linkedin.com/in/'.$this->faker->userName(),
            'lien_github' => 'https://github.com/'.$this->faker->userName(),
            'is_published' => false,
        ];
    }

    public function publié(): static
    {
        return $this->state(['is_published' => true]);
    }
}
