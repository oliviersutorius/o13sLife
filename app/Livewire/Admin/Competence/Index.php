<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Competence;

use App\Models\Competence;
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
    public string $categorie = '';

    #[Validate('required|string|max:255')]
    public string $nom = '';

    #[Validate('required|in:debutant,intermediaire,expert')]
    public string $niveau = 'intermediaire';

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
        $competence = Competence::findOrFail($id);

        $this->editingId = $id;
        $this->categorie = $competence->categorie;
        $this->nom = $competence->nom;
        $this->niveau = $competence->niveau;
        $this->showForm = true;
        $this->successMessage = '';
    }

    public function sauvegarder(): void
    {
        $this->validate();

        $data = [
            'categorie' => $this->categorie,
            'nom' => $this->nom,
            'niveau' => $this->niveau,
        ];

        if ($this->editingId !== null) {
            Competence::findOrFail($this->editingId)->update($data);
        } else {
            Competence::create(array_merge($data, ['is_published' => false]));
        }

        $this->successMessage = __('competence.sauvegarde_ok');
        $this->reinitialiserFormulaire();
        $this->chargerItems();
    }

    public function supprimer(int $id): void
    {
        Competence::findOrFail($id)->delete();
        $this->successMessage = __('competence.suppression_ok');
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
        $this->categorie = '';
        $this->nom = '';
        $this->niveau = 'intermediaire';
        $this->resetValidation();
    }

    private function chargerItems(): void
    {
        $this->items = Competence::orderBy('categorie')->orderBy('nom')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.competence.index');
    }
}
