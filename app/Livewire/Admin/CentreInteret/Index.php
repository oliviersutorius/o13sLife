<?php

declare(strict_types=1);

namespace App\Livewire\Admin\CentreInteret;

use App\Models\CentreInteret;
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
    public string $libelle = '';

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
        $item = CentreInteret::findOrFail($id);

        $this->editingId = $id;
        $this->libelle = $item->libelle;
        $this->showForm = true;
        $this->successMessage = '';
    }

    public function sauvegarder(): void
    {
        $this->validate();

        $data = ['libelle' => $this->libelle];

        if ($this->editingId !== null) {
            CentreInteret::findOrFail($this->editingId)->update($data);
        } else {
            CentreInteret::create(array_merge($data, ['is_published' => false]));
        }

        $this->successMessage = __('centre_interet.sauvegarde_ok');
        $this->reinitialiserFormulaire();
        $this->chargerItems();
    }

    public function togglePublication(int $id): void
    {
        $item = CentreInteret::findOrFail($id);
        $item->update(['is_published' => ! $item->is_published]);
        $this->successMessage = $item->is_published
            ? __('centre_interet.publication_ok')
            : __('centre_interet.depublication_ok');
        $this->chargerItems();
    }

    public function supprimer(int $id): void
    {
        CentreInteret::findOrFail($id)->delete();
        $this->successMessage = __('centre_interet.suppression_ok');
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
        $this->libelle = '';
        $this->resetValidation();
    }

    private function chargerItems(): void
    {
        $this->items = CentreInteret::orderBy('libelle')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.centre-interet.index');
    }
}
