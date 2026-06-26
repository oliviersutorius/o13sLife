<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;
use Illuminate\Database\Seeder;

class CvSeeder extends Seeder
{
    public function run(): void
    {
        Profil::create([
            'titre' => 'Développeur Full Stack',
            'email' => 'contact@example.com',
            'telephone' => '+33 6 00 00 00 00',
            'localisation' => 'Paris, France',
            'lien_linkedin' => 'https://linkedin.com/in/exemple',
            'lien_github' => 'https://github.com/exemple',
            'is_published' => true,
        ]);

        Experience::insert([
            [
                'titre_poste' => 'Développeur Full Stack',
                'entreprise' => 'Acme Corp',
                'date_debut' => '2022-01-01',
                'date_fin' => null,
                'description' => 'Développement et maintenance d\'une application Laravel en production. Mise en place de Livewire pour les interfaces réactives.',
                'technologies' => json_encode(['Laravel', 'Livewire', 'TailwindCSS', 'SQLite']),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre_poste' => 'Développeur PHP',
                'entreprise' => 'Startup XYZ',
                'date_debut' => '2019-06-01',
                'date_fin' => '2021-12-31',
                'description' => 'Conception et développement d\'APIs REST et de sites web pour des clients variés.',
                'technologies' => json_encode(['PHP', 'Symfony', 'MySQL', 'Vue.js']),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Formation::insert([
            [
                'ecole' => 'Université Paris Saclay',
                'annee' => 2019,
                'diplome' => 'Master Informatique — Génie Logiciel',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ecole' => 'IUT de Versailles',
                'annee' => 2017,
                'diplome' => 'DUT Informatique',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Competence::insert([
            ['categorie' => 'Langages', 'nom' => 'PHP', 'niveau' => 'expert', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['categorie' => 'Langages', 'nom' => 'JavaScript', 'niveau' => 'intermediaire', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['categorie' => 'Frameworks', 'nom' => 'Laravel', 'niveau' => 'expert', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['categorie' => 'Frameworks', 'nom' => 'Livewire', 'niveau' => 'intermediaire', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['categorie' => 'Outils', 'nom' => 'Git', 'niveau' => 'expert', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['categorie' => 'Outils', 'nom' => 'Docker', 'niveau' => 'debutant', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Langue::insert([
            ['langue' => 'Français', 'niveau' => 'Natif', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['langue' => 'Anglais', 'niveau' => 'Professionnel (C1)', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['langue' => 'Espagnol', 'niveau' => 'Intermédiaire (B1)', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        CentreInteret::insert([
            ['libelle' => 'Open source', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Randonnée', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Photographie', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Cuisine', 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
