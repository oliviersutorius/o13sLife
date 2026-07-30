<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Support\Facades\Hash;

function setAdminEnv(?string $email, ?string $password): void
{
    foreach (['ADMIN_EMAIL' => $email, 'ADMIN_PASSWORD' => $password] as $key => $value) {
        if ($value === null) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);

            continue;
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

afterEach(function () {
    setAdminEnv(null, null);
});

it('crée un admin avec l\'email et le mot de passe par défaut si aucune variable d\'env n\'est définie', function () {
    setAdminEnv(null, null);

    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', 'admin@o13slife.local')->first();

    expect($admin)->not->toBeNull();
});

it('crée un admin avec l\'email et le mot de passe fournis via ADMIN_EMAIL et ADMIN_PASSWORD', function () {
    setAdminEnv('custom-admin@example.com', 'un-mot-de-passe-solide');

    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', 'custom-admin@example.com')->first();

    expect($admin)->not->toBeNull();
    expect(Hash::check('un-mot-de-passe-solide', $admin->password))->toBeTrue();
});

it('ne recrée pas l\'admin si un compte existe déjà pour cet email', function () {
    setAdminEnv('custom-admin@example.com', 'premier-mot-de-passe');
    $this->seed(AdminUserSeeder::class);

    setAdminEnv('custom-admin@example.com', 'second-mot-de-passe');
    $this->seed(AdminUserSeeder::class);

    expect(User::where('email', 'custom-admin@example.com')->count())->toBe(1);

    $admin = User::where('email', 'custom-admin@example.com')->first();
    expect(Hash::check('premier-mot-de-passe', $admin->password))->toBeTrue();
});

it('génère un mot de passe aléatoire si ADMIN_PASSWORD est absent', function () {
    setAdminEnv('genere@example.com', null);

    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', 'genere@example.com')->first();

    expect($admin)->not->toBeNull();
    expect(Hash::check('password', $admin->password))->toBeFalse();
});
