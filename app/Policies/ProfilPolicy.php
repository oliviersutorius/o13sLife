<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Profil;
use App\Models\User;

// Le Profil est unique et géré exclusivement via les composants Livewire d'administration.
// Cette policy bloque toute action via les gates Laravel standard — le contrôle d'accès
// est assuré par le middleware 'auth' sur les routes /admin/*.
class ProfilPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Profil $profil): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Profil $profil): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Profil $profil): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profil $profil): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profil $profil): bool
    {
        return false;
    }
}
