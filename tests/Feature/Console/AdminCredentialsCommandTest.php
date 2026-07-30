<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('met à jour l\'email de l\'admin avec --email', function () {
    $admin = User::factory()->create(['email' => 'ancien@example.com']);

    $this->artisan('admin:credentials', ['--email' => 'nouveau@example.com'])
        ->assertSuccessful();

    expect($admin->refresh()->email)->toBe('nouveau@example.com');
});

it('met à jour le mot de passe de l\'admin avec --password', function () {
    $admin = User::factory()->create();

    $this->artisan('admin:credentials', ['--password' => 'un-nouveau-mot-de-passe'])
        ->assertSuccessful();

    expect(Hash::check('un-nouveau-mot-de-passe', $admin->refresh()->password))->toBeTrue();
});

it('met à jour email et mot de passe simultanément', function () {
    $admin = User::factory()->create();

    $this->artisan('admin:credentials', [
        '--email' => 'nouveau@example.com',
        '--password' => 'un-nouveau-mot-de-passe',
    ])->assertSuccessful();

    $admin->refresh();
    expect($admin->email)->toBe('nouveau@example.com');
    expect(Hash::check('un-nouveau-mot-de-passe', $admin->password))->toBeTrue();
});

it('échoue si aucune option n\'est fournie', function () {
    User::factory()->create();

    $this->artisan('admin:credentials')->assertFailed();
});

it('échoue avec un email invalide', function () {
    User::factory()->create();

    $this->artisan('admin:credentials', ['--email' => 'pas-un-email'])
        ->assertFailed();
});

it('échoue avec un mot de passe trop court', function () {
    User::factory()->create();

    $this->artisan('admin:credentials', ['--password' => '1234567'])
        ->assertFailed();
});

it('échoue si aucun compte administrateur n\'existe', function () {
    $this->artisan('admin:credentials', ['--email' => 'nouveau@example.com'])
        ->assertFailed();
});

it('échoue proprement si le nouvel email est déjà utilisé par un autre compte', function () {
    User::factory()->create(['email' => 'admin@example.com']);
    User::factory()->create(['email' => 'dejapris@example.com']);

    $this->artisan('admin:credentials', ['--email' => 'dejapris@example.com'])
        ->assertFailed();
});
