# /new-feature — Scaffold d'une nouvelle feature

Tu es l'agent **Développeur** du projet o13sLife. Crée le scaffold complet d'une nouvelle feature Laravel selon les conventions du projet.

## Utilisation

```
/new-feature #<issue> <NomDeLaFeature>
```

Exemple : `/new-feature #15 GestionProfil`

## Étapes à exécuter

### 1. Vérifier le contexte

- Confirmer que l'issue GitHub `#<issue>` existe et est assignée
- Vérifier qu'une branche `feature/#<issue>-<nom>` n'existe pas déjà

### 2. Créer la branche

```bash
git checkout -b feature/#<issue>-<nom-en-kebab-case> main
```

### 3. Générer les fichiers Laravel

```bash
# Modèle + migration + factory + seeder
php artisan make:model <NomDeLaFeature> -mfs

# Composant Livewire principal
php artisan make:livewire <NomDeLaFeature>/Index
php artisan make:livewire <NomDeLaFeature>/Form

# Policy
php artisan make:policy <NomDeLaFeature>Policy --model=<NomDeLaFeature>
```

### 4. Structure des fichiers à créer

```
app/
├── Models/<NomDeLaFeature>.php
├── Livewire/<NomDeLaFeature>/
│   ├── Index.php
│   └── Form.php
└── Policies/<NomDeLaFeature>Policy.php

resources/views/
└── livewire/<nom-en-kebab>/
    ├── index.blade.php
    └── form.blade.php

database/
├── migrations/<timestamp>_create_<nom>s_table.php
├── factories/<NomDeLaFeature>Factory.php
└── seeders/<NomDeLaFeature>Seeder.php

tests/
├── Unit/<NomDeLaFeature>Test.php
└── Feature/<NomDeLaFeature>/
    ├── IndexTest.php
    └── FormTest.php

lang/
├── fr/<nom-en-kebab>.php
└── en/<nom-en-kebab>.php
```

### 5. Conventions à respecter dans les fichiers générés

- `declare(strict_types=1);` en tête de chaque fichier PHP
- PHPDoc sur toutes les méthodes publiques
- Toutes les chaînes visibles via `__('<nom>.<clé>')`
- Attributs `aria-*` dans les vues Blade
- Classes Tailwind uniquement (pas de style inline)
- Tests Pest avec au minimum : happy path + cas d'erreur

### 6. Premier commit

```bash
git add .
git commit -m "feat(#<issue>): initialiser le scaffold de <NomDeLaFeature>"
```
