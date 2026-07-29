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

it('bloque après 5 tentatives échouées pour le même email', function () {
    User::factory()->create(['email' => 'admin@example.com']);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => 'mauvais-mot-de-passe',
        ])->assertSessionHasErrors('email');
    }

    $this->post(route('admin.login'), [
        'email' => 'admin@example.com',
        'password' => 'mauvais-mot-de-passe',
    ])->assertStatus(429);
});

it('ne bloque pas un autre compte depuis la même IP', function () {
    User::factory()->create(['email' => 'cible@example.com']);
    User::factory()->create(['email' => 'autre@example.com']);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('admin.login'), [
            'email' => 'cible@example.com',
            'password' => 'mauvais-mot-de-passe',
        ]);
    }

    $this->post(route('admin.login'), [
        'email' => 'autre@example.com',
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));
});

it('ne peut pas être contourné en variant la casse de l\'email', function () {
    User::factory()->create(['email' => 'admin@example.com']);

    $variantesCasse = [
        'admin@example.com',
        'Admin@Example.com',
        'ADMIN@EXAMPLE.COM',
        'AdMiN@eXaMpLe.CoM',
        'admin@Example.COM',
    ];

    foreach ($variantesCasse as $email) {
        $this->post(route('admin.login'), [
            'email' => $email,
            'password' => 'mauvais-mot-de-passe',
        ])->assertSessionHasErrors('email');
    }

    $this->post(route('admin.login'), [
        'email' => 'Admin@Example.com',
        'password' => 'mauvais-mot-de-passe',
    ])->assertStatus(429);
});
