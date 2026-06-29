<?php

declare(strict_types=1);

use App\Livewire\Admin\CentreInteret\Index;
use App\Models\CentreInteret;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('redirige un visiteur non connecté vers le login', function () {
    $this->get(route('admin.centre-interet.index'))
        ->assertRedirect(route('admin.login'));
});

it('affiche la page centres d\'intérêt à un admin connecté', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.centre-interet.index'))
        ->assertStatus(200)
        ->assertSee(__('centre_interet.titre_page'));
});

it('affiche le message vide quand aucun centre d\'intérêt', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('centre_interet.aucune_entree'));
});

it('crée un nouveau centre d\'intérêt', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->assertSet('showForm', true)
        ->set('libelle', 'Photographie')
        ->call('sauvegarder')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    $centreInteret = CentreInteret::first();
    expect($centreInteret)->not->toBeNull()
        ->and($centreInteret->libelle)->toBe('Photographie')
        ->and($centreInteret->is_published)->toBeFalse();
});

it('affiche les centres d\'intérêt existants dans la liste', function () {
    CentreInteret::factory()->create(['libelle' => 'Randonnée']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee('Randonnée');
});

it('modifie un centre d\'intérêt existant', function () {
    $centreInteret = CentreInteret::factory()->create(['libelle' => 'Ancien libellé']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('editer', $centreInteret->id)
        ->assertSet('editingId', $centreInteret->id)
        ->assertSet('libelle', 'Ancien libellé')
        ->set('libelle', 'Nouveau libellé')
        ->call('sauvegarder')
        ->assertHasNoErrors();

    expect($centreInteret->fresh()->libelle)->toBe('Nouveau libellé');
});

it('supprime un centre d\'intérêt', function () {
    $centreInteret = CentreInteret::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $centreInteret->id);

    expect(CentreInteret::find($centreInteret->id))->toBeNull();
});

it('affiche le message de succès après suppression', function () {
    $centreInteret = CentreInteret::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $centreInteret->id)
        ->assertSet('successMessage', __('centre_interet.suppression_ok'));
});

it('refuse un libellé vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('libelle', '')
        ->call('sauvegarder')
        ->assertHasErrors(['libelle' => 'required']);
});

it('annule le formulaire et réinitialise les champs', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('libelle', 'Un loisir')
        ->call('annuler')
        ->assertSet('showForm', false)
        ->assertSet('libelle', '');
});
