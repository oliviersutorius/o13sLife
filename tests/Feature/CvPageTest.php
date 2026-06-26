<?php

declare(strict_types=1);

use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;

beforeEach(function () {
    $this->withoutVite();
});

it('affiche la page CV publique', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('affiche le profil publié', function () {
    Profil::create([
        'titre' => 'Développeur Full Stack',
        'email' => 'test@example.com',
        'is_published' => true,
    ]);

    $this->get('/')->assertSee('Développeur Full Stack');
});

it('masque un profil non publié', function () {
    Profil::create([
        'titre' => 'Titre confidentiel',
        'email' => 'test@example.com',
        'is_published' => false,
    ]);

    $this->get('/')->assertDontSee('Titre confidentiel');
});

it('affiche les expériences publiées triées par date décroissante', function () {
    Experience::create([
        'titre_poste' => 'Poste Ancien',
        'entreprise' => 'Société A',
        'date_debut' => '2018-01-01',
        'date_fin' => '2020-12-31',
        'description' => 'Description ancienne',
        'is_published' => true,
    ]);

    Experience::create([
        'titre_poste' => 'Poste Récent',
        'entreprise' => 'Société B',
        'date_debut' => '2022-01-01',
        'date_fin' => null,
        'description' => 'Description récente',
        'is_published' => true,
    ]);

    $response = $this->get('/');
    $content = $response->getContent();

    expect(strpos($content, 'Poste Récent'))->toBeLessThan(strpos($content, 'Poste Ancien'));
});

it('masque les expériences non publiées', function () {
    Experience::create([
        'titre_poste' => 'Poste Secret',
        'entreprise' => 'Société Secrète',
        'date_debut' => '2022-01-01',
        'description' => 'Top secret',
        'is_published' => false,
    ]);

    $this->get('/')->assertDontSee('Poste Secret');
});

it('masque la rubrique expériences si vide', function () {
    $this->get('/')->assertDontSee(__('cv.experiences'));
});

it('affiche les formations publiées', function () {
    Formation::create([
        'ecole' => 'Université Test',
        'annee' => 2020,
        'diplome' => 'Master Test',
        'is_published' => true,
    ]);

    $this->get('/')->assertSee('Master Test')->assertSee('Université Test');
});

it('masque la rubrique formations si vide', function () {
    $this->get('/')->assertDontSee(__('cv.formations'));
});

it('affiche les compétences publiées groupées par catégorie', function () {
    Competence::create(['categorie' => 'Langages', 'nom' => 'PHP', 'niveau' => 'expert', 'is_published' => true]);

    $this->get('/')->assertSee('PHP')->assertSee('Langages');
});

it('masque la rubrique compétences si vide', function () {
    $this->get('/')->assertDontSee(__('cv.competences'));
});

it('affiche les langues publiées', function () {
    Langue::create(['langue' => 'Français', 'niveau' => 'Natif', 'is_published' => true]);

    $this->get('/')->assertSee('Français')->assertSee('Natif');
});

it('masque la rubrique langues si vide', function () {
    $this->get('/')->assertDontSee(__('cv.langues'));
});

it('affiche les centres d\'intérêt publiés', function () {
    CentreInteret::create(['libelle' => 'Randonnée', 'is_published' => true]);

    $this->get('/')->assertSee('Randonnée');
});

it('masque la rubrique centres d\'intérêt si vide', function () {
    $this->get('/')->assertDontSee(__('cv.centres_interet'));
});
