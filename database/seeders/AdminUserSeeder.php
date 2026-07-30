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
        $email = env('ADMIN_EMAIL', 'admin@o13slife.local');

        if (User::where('email', $email)->exists()) {
            return;
        }

        $password = env('ADMIN_PASSWORD');
        $isGenerated = $password === null;
        $password ??= Str::password(20);

        User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($isGenerated) {
            $this->command?->warn("Mot de passe admin généré : {$password}");
            $this->command?->warn('Notez-le : il ne sera plus affiché.');
        }
    }
}
