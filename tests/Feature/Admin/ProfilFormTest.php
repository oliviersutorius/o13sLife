<?php

declare(strict_types=1);

use App\Livewire\Admin\ProfilForm;
use App\Models\Profil;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

it('redirige un visiteur non connecté vers le login', function () {
    $this->get(route('admin.profil.edit'))
        ->assertRedirect(route('admin.login'));
});

it('affiche la page profil à un admin connecté', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.profil.edit'))
        ->assertStatus(200)
        ->assertSee(__('profil.titre_page'));
});

it('charge les données du profil existant au montage', function () {
    $profil = Profil::factory()->create([
        'titre' => 'Développeur Senior',
        'email' => 'dev@example.com',
    ]);

    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->assertSet('titre', 'Développeur Senior')
        ->assertSet('email', 'dev@example.com');
});

it('sauvegarde le profil en brouillon', function () {
    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->set('titre', 'Chef de Projet')
        ->set('email', 'chef@example.com')
        ->call('sauvegarder')
        ->assertHasNoErrors()
        ->assertSet('successMessage', __('profil.sauvegarde_ok'));

    $profil = Profil::first();
    expect($profil->titre)->toBe('Chef de Projet')
        ->and($profil->email)->toBe('chef@example.com')
        ->and($profil->is_published)->toBeFalse();
});

it('publie le profil et le rend visible', function () {
    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->set('titre', 'Architecte Logiciel')
        ->set('email', 'archi@example.com')
        ->call('publier')
        ->assertHasNoErrors()
        ->assertSet('successMessage', __('profil.publication_ok'))
        ->assertSet('is_published', true);

    expect(Profil::first()->is_published)->toBeTrue();
});

it('refuse un titre vide', function () {
    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->set('titre', '')
        ->set('email', 'test@example.com')
        ->call('sauvegarder')
        ->assertHasErrors(['titre' => 'required']);
});

it('refuse un email invalide', function () {
    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->set('titre', 'Développeur')
        ->set('email', 'email-invalide')
        ->call('sauvegarder')
        ->assertHasErrors(['email' => 'email']);
});

it('refuse une URL LinkedIn invalide', function () {
    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->set('titre', 'Développeur')
        ->set('email', 'dev@example.com')
        ->set('lien_linkedin', 'pas-une-url')
        ->call('sauvegarder')
        ->assertHasErrors(['lien_linkedin' => 'url']);
});

it('met à jour un profil existant sans en créer un nouveau', function () {
    Profil::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->set('titre', 'Nouveau Titre')
        ->set('email', 'nouveau@example.com')
        ->call('sauvegarder');

    expect(Profil::count())->toBe(1)
        ->and(Profil::first()->titre)->toBe('Nouveau Titre');
});

it('affiche le badge brouillon quand le profil est non publié', function () {
    Profil::factory()->create(['is_published' => false]);

    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->assertSee(__('profil.statut_brouillon'));
});

it('affiche le badge publié quand le profil est publié', function () {
    Profil::factory()->publié()->create();

    Livewire::actingAs($this->admin)
        ->test(ProfilForm::class)
        ->assertSee(__('profil.statut_publie'));
});
