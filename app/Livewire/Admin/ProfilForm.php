<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Profil;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilForm extends Component
{
    use WithFileUploads;

    #[Validate('nullable|image|max:2048')]
    public mixed $photo = null;

    #[Validate('required|string|max:255')]
    public string $titre = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $telephone = '';

    #[Validate('nullable|string|max:255')]
    public string $localisation = '';

    #[Validate('nullable|url|max:255')]
    public string $lien_linkedin = '';

    #[Validate('nullable|url|max:255')]
    public string $lien_github = '';

    public bool $is_published = false;

    public ?string $photo_actuelle = null;

    public string $successMessage = '';

    public ?int $profilId = null;

    public function mount(): void
    {
        $profil = Profil::first();

        if ($profil) {
            $this->profilId = $profil->id;
            $this->titre = $profil->titre;
            $this->email = $profil->email;
            $this->telephone = $profil->telephone ?? '';
            $this->localisation = $profil->localisation ?? '';
            $this->lien_linkedin = $profil->lien_linkedin ?? '';
            $this->lien_github = $profil->lien_github ?? '';
            $this->is_published = $profil->is_published;
            $this->photo_actuelle = $profil->photo;
        }
    }

    public function sauvegarder(): void
    {
        $this->validate();

        $profil = Profil::firstOrNew();

        $profil->titre = $this->titre;
        $profil->email = $this->email;
        $profil->telephone = $this->telephone ?: null;
        $profil->localisation = $this->localisation ?: null;
        $profil->lien_linkedin = $this->lien_linkedin ?: null;
        $profil->lien_github = $this->lien_github ?: null;

        if ($this->photo) {
            if ($profil->photo) {
                Storage::disk('public')->delete($profil->photo);
            }
            $profil->photo = $this->photo->store('profil', 'public');
            $this->photo_actuelle = $profil->photo;
            $this->photo = null;
        }

        $profil->save();

        $this->successMessage = __('profil.sauvegarde_ok');
    }

    public function publier(): void
    {
        $this->validate();

        $profil = Profil::firstOrNew();

        $profil->titre = $this->titre;
        $profil->email = $this->email;
        $profil->telephone = $this->telephone ?: null;
        $profil->localisation = $this->localisation ?: null;
        $profil->lien_linkedin = $this->lien_linkedin ?: null;
        $profil->lien_github = $this->lien_github ?: null;
        $profil->is_published = true;

        if ($this->photo) {
            if ($profil->photo) {
                Storage::disk('public')->delete($profil->photo);
            }
            $profil->photo = $this->photo->store('profil', 'public');
            $this->photo_actuelle = $profil->photo;
            $this->photo = null;
        }

        $profil->save();

        $this->is_published = true;
        $this->successMessage = __('profil.publication_ok');
    }

    public function render(): View
    {
        return view('livewire.admin.profil-form');
    }
}
