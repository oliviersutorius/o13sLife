<?php

declare(strict_types=1);

use App\Models\CentreInteret;
use App\Models\Competence;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Langue;
use App\Models\Profil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

dataset('entites_publiables', [
    'Profil' => [Profil::class, 'titre'],
    'Expérience' => [Experience::class, 'titre_poste'],
    'Formation' => [Formation::class, 'diplome'],
    'Compétence' => [Competence::class, 'nom'],
    'Langue' => [Langue::class, 'niveau'],
    'CentreInteret' => [CentreInteret::class, 'libelle'],
]);

// --- Ajout ---

it('crée un brouillon non publié, sans snapshot', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create();

    expect($item->is_published)->toBeFalse()
        ->and($item->published_snapshot)->toBeNull();
})->with('entites_publiables');

it('publish() fige le contenu courant et publie l\'élément', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Valeur Initiale']);

    $item->publish();

    expect($item->is_published)->toBeTrue()
        ->and($item->published_snapshot)->not->toBeNull()
        ->and($item->fresh()->withPublishedContent()->{$champ})->toBe('Valeur Initiale');
})->with('entites_publiables');

it('scopePublished() ne retourne que les éléments publiés', function (string $modelClass, string $champ) {
    $modelClass::factory()->create();
    $publie = $modelClass::factory()->create();
    $publie->publish();

    $resultats = $modelClass::published()->get();

    expect($resultats)->toHaveCount(1)
        ->and($resultats->first()->id)->toBe($publie->id);
})->with('entites_publiables');

// --- Modification ---

it('modifier le contenu après publication ne change pas la version publiée', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Valeur Publiée']);
    $item->publish();

    $item->update([$champ => 'Valeur Brouillon Modifiée']);

    expect($item->fresh()->{$champ})->toBe('Valeur Brouillon Modifiée')
        ->and($item->fresh()->withPublishedContent()->{$champ})->toBe('Valeur Publiée');
})->with('entites_publiables');

it('modifier un brouillon jamais publié n\'a pas de version publiée à préserver', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Brouillon Initial']);

    $item->update([$champ => 'Brouillon Modifié']);

    expect($item->fresh()->is_published)->toBeFalse()
        ->and($item->fresh()->published_snapshot)->toBeNull()
        ->and($item->fresh()->withPublishedContent()->{$champ})->toBe('Brouillon Modifié');
})->with('entites_publiables');

it('republier applique la nouvelle version au contenu publié', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Version 1']);
    $item->publish();
    $item->update([$champ => 'Version 2']);

    $item->publish();

    expect($item->fresh()->withPublishedContent()->{$champ})->toBe('Version 2');
})->with('entites_publiables');

it('unpublish() masque l\'élément sans effacer le snapshot publié', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Valeur Publiée']);
    $item->publish();

    $item->unpublish();

    expect($item->fresh()->is_published)->toBeFalse()
        ->and($item->fresh()->withPublishedContent()->{$champ})->toBe('Valeur Publiée');
})->with('entites_publiables');

it('republier après une dépublication restaure la visibilité', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create();
    $item->publish();
    $item->unpublish();

    $item->publish();

    expect($item->fresh()->is_published)->toBeTrue()
        ->and($modelClass::published()->count())->toBe(1);
})->with('entites_publiables');

// --- Suppression ---

it('supprimer un élément publié le retire de scopePublished()', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create();
    $item->publish();

    expect($modelClass::published()->count())->toBe(1);

    $item->delete();

    expect($modelClass::published()->count())->toBe(0)
        ->and($modelClass::find($item->id))->toBeNull();
})->with('entites_publiables');

it('supprimer un brouillon jamais publié ne laisse aucune trace', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create();

    $item->delete();

    expect($modelClass::find($item->id))->toBeNull()
        ->and($modelClass::published()->count())->toBe(0);
})->with('entites_publiables');

it('supprimer un élément n\'affecte pas les autres éléments publiés', function (string $modelClass, string $champ) {
    $aSupprimer = $modelClass::factory()->create();
    $aSupprimer->publish();

    $aConserver = $modelClass::factory()->create([$champ => 'Toujours Là']);
    $aConserver->publish();

    $aSupprimer->delete();

    $resultats = $modelClass::published()->get();
    expect($resultats)->toHaveCount(1)
        ->and($resultats->first()->id)->toBe($aConserver->id);
})->with('entites_publiables');

// --- Cas limite commun aux trois opérations ---

it('withPublishedContent() ne modifie rien quand published_snapshot est vide', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Brouillon Jamais Publié']);

    $result = $item->withPublishedContent();

    expect($result->{$champ})->toBe('Brouillon Jamais Publié');
})->with('entites_publiables');

it('withPublishedContent() ne modifie pas l\'instance originale (clone)', function (string $modelClass, string $champ) {
    $item = $modelClass::factory()->create([$champ => 'Ancien']);
    $item->publish();
    $item->update([$champ => 'Brouillon']);

    $frais = $item->fresh();
    $clone = $frais->withPublishedContent();

    expect($clone)->not->toBe($frais)
        ->and($frais->{$champ})->toBe('Brouillon');
})->with('entites_publiables');
