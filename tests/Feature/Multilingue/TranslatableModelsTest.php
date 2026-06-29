<?php

declare(strict_types=1);

use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;

it('le modèle Profil stocke et retourne le titre traduit', function () {
    $profil = Profil::factory()->create(['titre' => ['fr' => 'Développeur', 'en' => 'Developer']]);

    app()->setLocale('fr');
    expect($profil->titre)->toBe('Développeur');

    app()->setLocale('en');
    expect($profil->titre)->toBe('Developer');
});

it('le modèle Experience stocke et retourne les champs traduits', function () {
    $exp = Experience::factory()->create([
        'titre_poste' => ['fr' => 'Développeur Full Stack', 'en' => 'Full Stack Developer'],
        'description' => ['fr' => 'Description FR', 'en' => 'Description EN'],
    ]);

    app()->setLocale('en');
    expect($exp->titre_poste)->toBe('Full Stack Developer')
        ->and($exp->description)->toBe('Description EN');
});

it('le modèle Formation stocke et retourne le diplôme traduit', function () {
    $formation = Formation::factory()->create([
        'diplome' => ['fr' => 'Master Informatique', 'en' => 'Master Computer Science'],
    ]);

    app()->setLocale('en');
    expect($formation->diplome)->toBe('Master Computer Science');
});

it('le modèle Competence stocke et retourne les champs traduits', function () {
    $competence = Competence::factory()->create([
        'categorie' => ['fr' => 'Langages', 'en' => 'Languages'],
        'nom' => ['fr' => 'PHP', 'en' => 'PHP'],
    ]);

    app()->setLocale('en');
    expect($competence->categorie)->toBe('Languages');
});

it('le modèle Langue stocke et retourne le niveau traduit', function () {
    $langue = Langue::factory()->create([
        'niveau' => ['fr' => 'Natif', 'en' => 'Native'],
    ]);

    app()->setLocale('en');
    expect($langue->niveau)->toBe('Native');
});

it('le modèle CentreInteret stocke et retourne le libellé traduit', function () {
    $ci = CentreInteret::factory()->create([
        'libelle' => ['fr' => 'Randonnée', 'en' => 'Hiking'],
    ]);

    app()->setLocale('en');
    expect($ci->libelle)->toBe('Hiking');
});

it('retourne la valeur française en fallback si la traduction est absente', function () {
    config(['app.fallback_locale' => 'fr']);

    $profil = Profil::factory()->create(['titre' => ['fr' => 'Développeur']]);

    expect($profil->getTranslation('titre', 'en', false))->toBeEmpty();
    expect($profil->getTranslationWithFallback('titre', 'en'))->toBe('Développeur');
});
