<?php

declare(strict_types=1);

use App\Livewire\Admin\Langue\Index;
use App\Models\Langue;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('redirige un visiteur non connecté vers le login', function () {
    $this->get(route('admin.langue.index'))
        ->assertRedirect(route('admin.login'));
});

it('affiche la page langues à un admin connecté', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.langue.index'))
        ->assertStatus(200)
        ->assertSee(__('langue.titre_page'));
});

it('affiche le message vide quand aucune langue', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('langue.aucune_entree'));
});

it('crée une nouvelle langue', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->assertSet('showForm', true)
        ->set('langue', 'Anglais')
        ->set('niveau', 'Professionnel (C1)')
        ->call('sauvegarder')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    $langue = Langue::first();
    expect($langue)->not->toBeNull()
        ->and($langue->langue)->toBe('Anglais')
        ->and($langue->niveau)->toBe('Professionnel (C1)')
        ->and($langue->is_published)->toBeFalse();
});

it('affiche les langues existantes dans la liste', function () {
    Langue::factory()->create([
        'langue' => 'Espagnol',
        'niveau' => 'Intermédiaire (B2)',
    ]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee('Espagnol')
        ->assertSee('Intermédiaire (B2)');
});

it('modifie une langue existante', function () {
    $langue = Langue::factory()->create(['langue' => 'Ancienne langue', 'niveau' => 'Débutant']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('editer', $langue->id)
        ->assertSet('editingId', $langue->id)
        ->assertSet('langue', 'Ancienne langue')
        ->set('langue', 'Nouvelle langue')
        ->set('niveau', 'Expert')
        ->call('sauvegarder')
        ->assertHasNoErrors();

    $updated = $langue->fresh();
    expect($updated->langue)->toBe('Nouvelle langue')
        ->and($updated->niveau)->toBe('Expert');
});

it('supprime une langue', function () {
    $langue = Langue::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $langue->id);

    expect(Langue::find($langue->id))->toBeNull();
});

it('affiche le message de succès après suppression', function () {
    $langue = Langue::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $langue->id)
        ->assertSet('successMessage', __('langue.suppression_ok'));
});

it('refuse une langue vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('langue', '')
        ->set('niveau', 'Natif')
        ->call('sauvegarder')
        ->assertHasErrors(['langue' => 'required']);
});

it('refuse un niveau vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('langue', 'Français')
        ->set('niveau', '')
        ->call('sauvegarder')
        ->assertHasErrors(['niveau' => 'required']);
});

it('annule le formulaire et réinitialise les champs', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('langue', 'Français')
        ->call('annuler')
        ->assertSet('showForm', false)
        ->assertSet('langue', '');
});

it('publie une langue et affiche le message de succès', function () {
    $langue = Langue::factory()->create(['is_published' => false]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('togglePublication', $langue->id)
        ->assertSet('successMessage', __('langue.publication_ok'));

    expect($langue->fresh()->is_published)->toBeTrue();
});

it('dépublie une langue et affiche le message de succès', function () {
    $langue = Langue::factory()->publié()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('togglePublication', $langue->id)
        ->assertSet('successMessage', __('langue.depublication_ok'));

    expect($langue->fresh()->is_published)->toBeFalse();
});

it('affiche le badge publié pour une langue publiée', function () {
    Langue::factory()->publié()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('langue.statut_publie'));
});

it('affiche le badge brouillon pour une langue non publiée', function () {
    Langue::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('langue.statut_brouillon'));
});
