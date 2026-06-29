<?php

declare(strict_types=1);

use App\Models\User;

it('affiche la page de login', function () {
    $this->get(route('admin.login'))->assertStatus(200);
});

it('redirige vers le dashboard si déjà connecté', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.login'))
        ->assertRedirect(route('admin.dashboard'));
});

it('connecte un utilisateur avec des identifiants valides', function () {
    $user = User::factory()->create();

    $this->post(route('admin.login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('refuse des identifiants invalides', function () {
    User::factory()->create(['email' => 'admin@example.com']);

    $this->post(route('admin.login'), [
        'email' => 'admin@example.com',
        'password' => 'mauvais-mot-de-passe',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('protège le dashboard contre les visiteurs non connectés', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.login'));
});

it('affiche le dashboard pour un utilisateur connecté', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertStatus(200);
});

it('déconnecte l\'utilisateur', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.logout'))
        ->assertRedirect(route('cv'));

    $this->assertGuest();
});

it('empêche l\'accès au dashboard après déconnexion', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('admin.logout'));

    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.login'));
});
