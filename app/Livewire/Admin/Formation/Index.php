<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Formation;

use App\Models\Formation;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Index extends Component
{
    public Collection $items;

    public ?int $editingId = null;

    public bool $showForm = false;

    #[Validate('required|string|max:255')]
    public string $ecole = '';

    #[Validate('required|integer|min:1900|max:2100')]
    public string $annee = '';

    #[Validate('required|string|max:255')]
    public string $diplome = '';

    public string $successMessage = '';

    public function mount(): void
    {
        $this->chargerItems();
    }

    public function creer(): void
    {
        $this->reinitialiserFormulaire();
        $this->showForm = true;
    }

    public function editer(int $id): void
    {
        $formation = Formation::findOrFail($id);

        $this->editingId = $id;
        $this->ecole = $formation->ecole;
        $this->annee = (string) $formation->annee;
        $this->diplome = $formation->diplome;
        $this->showForm = true;
        $this->successMessage = '';
    }

    public function sauvegarder(): void
    {
        $this->validate();

        $data = [
            'ecole' => $this->ecole,
            'annee' => (int) $this->annee,
            'diplome' => $this->diplome,
        ];

        if ($this->editingId !== null) {
            Formation::findOrFail($this->editingId)->update($data);
        } else {
            Formation::create(array_merge($data, ['is_published' => false]));
        }

        $this->successMessage = __('formation.sauvegarde_ok');
        $this->reinitialiserFormulaire();
        $this->chargerItems();
    }

    public function supprimer(int $id): void
    {
        Formation::findOrFail($id)->delete();
        $this->successMessage = __('formation.suppression_ok');
        $this->chargerItems();
    }

    public function annuler(): void
    {
        $this->reinitialiserFormulaire();
    }

    private function reinitialiserFormulaire(): void
    {
        $this->editingId = null;
        $this->showForm = false;
        $this->ecole = '';
        $this->annee = '';
        $this->diplome = '';
        $this->resetValidation();
    }

    private function chargerItems(): void
    {
        $this->items = Formation::orderBy('annee', 'desc')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.formation.index');
    }
}
