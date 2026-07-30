<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        $email = env('ADMIN_EMAIL', 'admin@o13slife.local');
        $password = env('ADMIN_PASSWORD');
        $isGenerated = $password === null;
        $password ??= Str::password(20);

        User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($isGenerated) {
            $path = storage_path('app/private/admin-generated-password.txt');
            file_put_contents($path, $password);
            chmod($path, 0600);

            $this->command?->warn("Mot de passe admin généré et écrit dans : {$path}");
            $this->command?->warn('Notez-le puis supprimez ce fichier.');
        }
    }
}
