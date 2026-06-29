<?php

declare(strict_types=1);

use App\Livewire\Admin\Competence\Index;
use App\Models\Competence;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('redirige un visiteur non connecté vers le login', function () {
    $this->get(route('admin.competence.index'))
        ->assertRedirect(route('admin.login'));
});

it('affiche la page compétences à un admin connecté', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.competence.index'))
        ->assertStatus(200)
        ->assertSee(__('competence.titre_page'));
});

it('affiche le message vide quand aucune compétence', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('competence.aucune_entree'));
});

it('crée une nouvelle compétence', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->assertSet('showForm', true)
        ->set('categorie', 'Backend')
        ->set('nom', 'Laravel')
        ->set('niveau', 'expert')
        ->call('sauvegarder')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    $competence = Competence::first();
    expect($competence)->not->toBeNull()
        ->and($competence->categorie)->toBe('Backend')
        ->and($competence->nom)->toBe('Laravel')
        ->and($competence->niveau)->toBe('expert')
        ->and($competence->is_published)->toBeFalse();
});

it('affiche les compétences existantes dans la liste', function () {
    Competence::factory()->create([
        'categorie' => 'Frontend',
        'nom' => 'Vue.js',
    ]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee('Frontend')
        ->assertSee('Vue.js');
});

it('modifie une compétence existante', function () {
    $competence = Competence::factory()->create(['nom' => 'Ancien nom', 'niveau' => 'debutant']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('editer', $competence->id)
        ->assertSet('editingId', $competence->id)
        ->assertSet('nom', 'Ancien nom')
        ->set('nom', 'Nouveau nom')
        ->set('niveau', 'expert')
        ->call('sauvegarder')
        ->assertHasNoErrors();

    $updated = $competence->fresh();
    expect($updated->nom)->toBe('Nouveau nom')
        ->and($updated->niveau)->toBe('expert');
});

it('supprime une compétence', function () {
    $competence = Competence::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $competence->id);

    expect(Competence::find($competence->id))->toBeNull();
});

it('affiche le message de succès après suppression', function () {
    $competence = Competence::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $competence->id)
        ->assertSet('successMessage', __('competence.suppression_ok'));
});

it('refuse une catégorie vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('categorie', '')
        ->set('nom', 'PHP')
        ->set('niveau', 'expert')
        ->call('sauvegarder')
        ->assertHasErrors(['categorie' => 'required']);
});

it('refuse un niveau invalide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('categorie', 'Backend')
        ->set('nom', 'PHP')
        ->set('niveau', 'invalide')
        ->call('sauvegarder')
        ->assertHasErrors(['niveau' => 'in']);
});

it('annule le formulaire et réinitialise les champs', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('categorie', 'Backend')
        ->call('annuler')
        ->assertSet('showForm', false)
        ->assertSet('categorie', '');
});

it('publie une compétence et affiche le message de succès', function () {
    $competence = Competence::factory()->create(['is_published' => false]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('togglePublication', $competence->id)
        ->assertSet('successMessage', __('competence.publication_ok'));

    expect($competence->fresh()->is_published)->toBeTrue();
});

it('dépublie une compétence et affiche le message de succès', function () {
    $competence = Competence::factory()->publié()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('togglePublication', $competence->id)
        ->assertSet('successMessage', __('competence.depublication_ok'));

    expect($competence->fresh()->is_published)->toBeFalse();
});

it('affiche le badge publié pour une compétence publiée', function () {
    Competence::factory()->publié()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('competence.statut_publie'));
});

it('affiche le badge brouillon pour une compétence non publiée', function () {
    Competence::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('competence.statut_brouillon'));
});
