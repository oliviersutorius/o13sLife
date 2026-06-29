<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Langue;

use App\Models\Langue;
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
    public string $langue = '';

    #[Validate('required|string|max:255')]
    public string $niveau = '';

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
        $item = Langue::findOrFail($id);

        $this->editingId = $id;
        $this->langue = $item->langue;
        $this->niveau = $item->niveau;
        $this->showForm = true;
        $this->successMessage = '';
    }

    public function sauvegarder(): void
    {
        $this->validate();

        $data = [
            'langue' => $this->langue,
            'niveau' => $this->niveau,
        ];

        if ($this->editingId !== null) {
            Langue::findOrFail($this->editingId)->update($data);
        } else {
            Langue::create(array_merge($data, ['is_published' => false]));
        }

        $this->successMessage = __('langue.sauvegarde_ok');
        $this->reinitialiserFormulaire();
        $this->chargerItems();
    }

    public function supprimer(int $id): void
    {
        Langue::findOrFail($id)->delete();
        $this->successMessage = __('langue.suppression_ok');
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
        $this->langue = '';
        $this->niveau = '';
        $this->resetValidation();
    }

    private function chargerItems(): void
    {
        $this->items = Langue::orderBy('langue')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.langue.index');
    }
}
