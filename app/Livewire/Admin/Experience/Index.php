<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Experience;

use App\Models\Experience;
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
    public string $titre_poste = '';

    #[Validate('required|string|max:255')]
    public string $entreprise = '';

    #[Validate('required|date')]
    public string $date_debut = '';

    #[Validate('nullable|date|after_or_equal:date_debut')]
    public string $date_fin = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('nullable|string')]
    public string $technologies = '';

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
        $experience = Experience::findOrFail($id);

        $this->editingId = $id;
        $this->titre_poste = $experience->titre_poste;
        $this->entreprise = $experience->entreprise;
        $this->date_debut = $experience->date_debut->format('Y-m-d');
        $this->date_fin = $experience->date_fin?->format('Y-m-d') ?? '';
        $this->description = $experience->description;
        $this->technologies = implode(', ', $experience->technologies ?? []);
        $this->showForm = true;
        $this->successMessage = '';
    }

    public function sauvegarder(): void
    {
        $this->validate();

        $technologies = array_values(array_filter(
            array_map('trim', explode(',', $this->technologies))
        ));

        $data = [
            'titre_poste' => $this->titre_poste,
            'entreprise' => $this->entreprise,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin ?: null,
            'description' => $this->description,
            'technologies' => $technologies,
        ];

        if ($this->editingId !== null) {
            Experience::findOrFail($this->editingId)->update($data);
        } else {
            Experience::create(array_merge($data, ['is_published' => false]));
        }

        $this->successMessage = __('experience.sauvegarde_ok');
        $this->reinitialiserFormulaire();
        $this->chargerItems();
    }

    public function togglePublication(int $id): void
    {
        $item = Experience::findOrFail($id);
        $item->update(['is_published' => ! $item->is_published]);
        $this->successMessage = $item->is_published
            ? __('experience.publication_ok')
            : __('experience.depublication_ok');
        $this->chargerItems();
    }

    public function supprimer(int $id): void
    {
        Experience::findOrFail($id)->delete();
        $this->successMessage = __('experience.suppression_ok');
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
        $this->titre_poste = '';
        $this->entreprise = '';
        $this->date_debut = '';
        $this->date_fin = '';
        $this->description = '';
        $this->technologies = '';
        $this->resetValidation();
    }

    private function chargerItems(): void
    {
        $this->items = Experience::orderBy('date_debut', 'desc')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.experience.index');
    }
}
