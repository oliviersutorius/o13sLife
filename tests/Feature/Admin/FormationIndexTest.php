<?php

declare(strict_types=1);

use App\Livewire\Admin\Formation\Index;
use App\Models\Formation;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('redirige un visiteur non connecté vers le login', function () {
    $this->get(route('admin.formation.index'))
        ->assertRedirect(route('admin.login'));
});

it('affiche la page formations à un admin connecté', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.formation.index'))
        ->assertStatus(200)
        ->assertSee(__('formation.titre_page'));
});

it('affiche le message vide quand aucune formation', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('formation.aucune_entree'));
});

it('crée une nouvelle formation', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->assertSet('showForm', true)
        ->set('ecole', 'EPITECH')
        ->set('annee', '2020')
        ->set('diplome', 'Titre Expert Informatique')
        ->call('sauvegarder')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    $formation = Formation::first();
    expect($formation)->not->toBeNull()
        ->and($formation->ecole)->toBe('EPITECH')
        ->and($formation->annee)->toBe(2020)
        ->and($formation->is_published)->toBeFalse();
});

it('affiche les formations existantes dans la liste', function () {
    Formation::factory()->create([
        'ecole' => 'Université Paris-Saclay',
        'diplome' => 'Master Informatique',
    ]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee('Université Paris-Saclay')
        ->assertSee('Master Informatique');
});

it('modifie une formation existante', function () {
    $formation = Formation::factory()->create(['ecole' => 'Ancienne École']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('editer', $formation->id)
        ->assertSet('editingId', $formation->id)
        ->assertSet('ecole', 'Ancienne École')
        ->set('ecole', 'Nouvelle École')
        ->call('sauvegarder')
        ->assertHasNoErrors();

    expect($formation->fresh()->ecole)->toBe('Nouvelle École');
});

it('supprime une formation', function () {
    $formation = Formation::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $formation->id);

    expect(Formation::find($formation->id))->toBeNull();
});

it('affiche le message de succès après suppression', function () {
    $formation = Formation::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $formation->id)
        ->assertSet('successMessage', __('formation.suppression_ok'));
});

it('refuse une école vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('ecole', '')
        ->set('annee', '2020')
        ->set('diplome', 'Master')
        ->call('sauvegarder')
        ->assertHasErrors(['ecole' => 'required']);
});

it('refuse un diplôme vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('ecole', 'EPITECH')
        ->set('annee', '2020')
        ->set('diplome', '')
        ->call('sauvegarder')
        ->assertHasErrors(['diplome' => 'required']);
});

it('annule le formulaire et réinitialise les champs', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('ecole', 'Une école')
        ->call('annuler')
        ->assertSet('showForm', false)
        ->assertSet('ecole', '');
});
