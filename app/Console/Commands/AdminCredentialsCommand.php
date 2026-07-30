<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('admin:credentials {--email= : Nouvel email de connexion} {--password= : Nouveau mot de passe (8 caractères minimum)}')]
#[Description("Change l'email et/ou le mot de passe du compte administrateur, en local comme en production.")]
class AdminCredentialsCommand extends Command
{
    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');

        if ($email === null && $password === null) {
            $this->error('Précisez au moins une option : --email ou --password.');

            return self::FAILURE;
        }

        if ($email !== null && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Email invalide : {$email}");

            return self::FAILURE;
        }

        if ($password !== null && mb_strlen($password) < 8) {
            $this->error('Le mot de passe doit contenir au moins 8 caractères.');

            return self::FAILURE;
        }

        $admin = User::first();

        if ($admin === null) {
            $this->error("Aucun compte administrateur trouvé. Lancez d'abord `php artisan db:seed`.");

            return self::FAILURE;
        }

        if ($email !== null) {
            $admin->email = $email;
        }

        if ($password !== null) {
            $admin->password = Hash::make($password);
        }

        $admin->save();

        if ($email !== null) {
            $this->info("Email admin mis à jour : {$email}");
        }

        if ($password !== null) {
            $this->info('Mot de passe admin mis à jour.');
        }

        return self::SUCCESS;
    }
}
