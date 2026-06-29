<?php

declare(strict_types=1);

use App\Livewire\Admin\Experience\Index;
use App\Models\Experience;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('redirige un visiteur non connecté vers le login', function () {
    $this->get(route('admin.experience.index'))
        ->assertRedirect(route('admin.login'));
});

it('affiche la page expériences à un admin connecté', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.experience.index'))
        ->assertStatus(200)
        ->assertSee(__('experience.titre_page'));
});

it('affiche le message vide quand aucune expérience', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('experience.aucune_entree'));
});

it('crée une nouvelle expérience', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->assertSet('showForm', true)
        ->set('titre_poste', 'Développeur Laravel')
        ->set('entreprise', 'Acme Corp')
        ->set('date_debut', '2022-01-01')
        ->set('description', 'Développement d\'applications web.')
        ->call('sauvegarder')
        ->assertHasNoErrors()
        ->assertSet('showForm', false);

    $experience = Experience::first();
    expect($experience)->not->toBeNull()
        ->and($experience->titre_poste)->toBe('Développeur Laravel')
        ->and($experience->entreprise)->toBe('Acme Corp')
        ->and($experience->is_published)->toBeFalse();
});

it('affiche les expériences existantes dans la liste', function () {
    Experience::factory()->create([
        'titre_poste' => 'Ingénieur Backend',
        'entreprise' => 'Tech SA',
    ]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee('Ingénieur Backend')
        ->assertSee('Tech SA');
});

it('modifie une expérience existante', function () {
    $experience = Experience::factory()->create(['titre_poste' => 'Ancien Titre']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('editer', $experience->id)
        ->assertSet('editingId', $experience->id)
        ->assertSet('titre_poste', 'Ancien Titre')
        ->set('titre_poste', 'Nouveau Titre')
        ->call('sauvegarder')
        ->assertHasNoErrors();

    expect($experience->fresh()->titre_poste)->toBe('Nouveau Titre');
});

it('supprime une expérience', function () {
    $experience = Experience::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $experience->id);

    expect(Experience::find($experience->id))->toBeNull();
});

it('affiche le message de succès après suppression', function () {
    $experience = Experience::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('supprimer', $experience->id)
        ->assertSet('successMessage', __('experience.suppression_ok'));
});

it('refuse un titre de poste vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('titre_poste', '')
        ->set('entreprise', 'Acme')
        ->set('date_debut', '2022-01-01')
        ->set('description', 'Description')
        ->call('sauvegarder')
        ->assertHasErrors(['titre_poste' => 'required']);
});

it('refuse une entreprise vide', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('titre_poste', 'Dev')
        ->set('entreprise', '')
        ->set('date_debut', '2022-01-01')
        ->set('description', 'Description')
        ->call('sauvegarder')
        ->assertHasErrors(['entreprise' => 'required']);
});

it('annule le formulaire et réinitialise les champs', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('titre_poste', 'Un titre')
        ->call('annuler')
        ->assertSet('showForm', false)
        ->assertSet('titre_poste', '');
});

it('convertit les technologies en tableau lors de la sauvegarde', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('creer')
        ->set('titre_poste', 'Dev')
        ->set('entreprise', 'Acme')
        ->set('date_debut', '2022-01-01')
        ->set('description', 'Description')
        ->set('technologies', 'PHP, Laravel, Vue.js')
        ->call('sauvegarder')
        ->assertHasNoErrors();

    $experience = Experience::first();
    expect($experience->technologies)->toBe(['PHP', 'Laravel', 'Vue.js']);
});

it('publie une expérience et affiche le message de succès', function () {
    $experience = Experience::factory()->create(['is_published' => false]);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('togglePublication', $experience->id)
        ->assertSet('successMessage', __('experience.publication_ok'));

    expect($experience->fresh()->is_published)->toBeTrue();
});

it('dépublie une expérience et affiche le message de succès', function () {
    $experience = Experience::factory()->publié()->create();

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->call('togglePublication', $experience->id)
        ->assertSet('successMessage', __('experience.depublication_ok'));

    expect($experience->fresh()->is_published)->toBeFalse();
});

it('affiche le badge publié pour une expérience publiée', function () {
    Experience::factory()->publié()->create(['titre_poste' => 'Poste Publié']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('experience.statut_publie'));
});

it('affiche le badge brouillon pour une expérience non publiée', function () {
    Experience::factory()->create(['titre_poste' => 'Poste Brouillon']);

    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->assertSee(__('experience.statut_brouillon'));
});
