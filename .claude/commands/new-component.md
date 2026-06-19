# /new-component — Nouveau composant Livewire

Tu es l'agent **Développeur** du projet o13sLife. Crée un nouveau composant Livewire complet selon les conventions du projet.

## Utilisation

```
/new-component #<issue> <Dossier/NomDuComposant>
```

Exemple : `/new-component #18 Profil/AvatarUpload`

## Étapes à exécuter

### 1. Générer le composant

```bash
php artisan make:livewire <Dossier/NomDuComposant>
```

### 2. Structure du composant PHP généré

```php
<?php

declare(strict_types=1);

namespace App\Livewire\<Dossier>;

use Livewire\Component;

class <NomDuComposant> extends Component
{
    // Propriétés publiques liées à la vue

    /**
     * Règles de validation.
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Rendu du composant.
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.<dossier>.<nom-en-kebab>');
    }
}
```

### 3. Structure de la vue Blade générée

```blade
<div>
    {{-- Titre accessible --}}
    <h2 id="<nom>-title">{{ __('<module>.<nom>.title') }}</h2>

    {{-- Contenu du composant --}}
    {{-- Classes Tailwind uniquement, pas de style inline --}}
    {{-- Attributs aria-* sur tous les éléments interactifs --}}
</div>
```

### 4. Fichiers à créer / mettre à jour

- `app/Livewire/<Dossier>/<NomDuComposant>.php`
- `resources/views/livewire/<dossier>/<nom-en-kebab>.blade.php`
- `tests/Feature/Livewire/<Dossier>/<NomDuComposant>Test.php`
- `lang/fr/<module>.php` — ajouter les clés du composant
- `lang/en/<module>.php` — ajouter les clés du composant

### 5. Tests minimaux à générer

```php
<?php

declare(strict_types=1);

use App\Livewire\<Dossier>\<NomDuComposant>;
use Livewire\Livewire;

it('peut être rendu', function () {
    Livewire::test(<NomDuComposant>::class)
        ->assertStatus(200);
});

it('affiche les éléments attendus', function () {
    Livewire::test(<NomDuComposant>::class)
        ->assertSee(__('<module>.<nom>.title'));
});
```

### 6. Checklist avant commit

- [ ] `declare(strict_types=1);` présent
- [ ] PHPDoc sur les méthodes publiques
- [ ] Toutes les chaînes via `__()`
- [ ] Attributs `aria-*` dans la vue
- [ ] Classes Tailwind uniquement
- [ ] Tests écrits et passants

### 7. Commit

```bash
git add .
git commit -m "feat(#<issue>): ajouter le composant <NomDuComposant>"
```
