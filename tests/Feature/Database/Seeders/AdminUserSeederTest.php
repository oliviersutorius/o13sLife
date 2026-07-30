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

    $path = storage_path('app/private/admin-generated-password.txt');
    if (file_exists($path)) {
        unlink($path);
    }
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

it('ne crée pas de second admin si ADMIN_EMAIL change alors qu\'un admin existe déjà', function () {
    setAdminEnv('premier-admin@example.com', 'premier-mot-de-passe');
    $this->seed(AdminUserSeeder::class);

    setAdminEnv('second-admin@example.com', 'second-mot-de-passe');
    $this->seed(AdminUserSeeder::class);

    expect(User::count())->toBe(1);
    expect(User::where('email', 'second-admin@example.com')->exists())->toBeFalse();

    $admin = User::first();
    expect($admin->email)->toBe('premier-admin@example.com');
    expect(Hash::check('premier-mot-de-passe', $admin->password))->toBeTrue();
});

it('génère un mot de passe aléatoire et l\'écrit dans un fichier privé si ADMIN_PASSWORD est absent', function () {
    setAdminEnv('genere@example.com', null);

    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', 'genere@example.com')->first();
    $path = storage_path('app/private/admin-generated-password.txt');

    expect($admin)->not->toBeNull();
    expect(Hash::check('password', $admin->password))->toBeFalse();
    expect(file_exists($path))->toBeTrue();
    expect(Hash::check(file_get_contents($path), $admin->password))->toBeTrue();
});
