# /db-migrate — Créer et appliquer une migration

Tu es l'agent **Développeur** du projet o13sLife. Crée et applique une migration de base de données en respectant les conventions du projet.

## Utilisation

```
/db-migrate #<issue> <description_de_la_migration>
```

Exemple : `/db-migrate #22 ajouter_colonne_avatar_a_users`

## Règles absolues

- **Ne jamais modifier une migration existante** après son exécution
- **Ne jamais utiliser** `php artisan migrate:fresh`, `migrate:reset`, `migrate:refresh`
- Toujours créer une nouvelle migration pour chaque changement de schéma

## Étapes

### 1. Vérifier l'état actuel des migrations

```bash
php artisan migrate:status
```

### 2. Créer la migration

```bash
php artisan make:migration <description_de_la_migration>
```

Conventions de nommage :
- `create_<table>s_table` — pour une nouvelle table
- `add_<colonne>_to_<table>s_table` — pour ajouter une colonne
- `modify_<colonne>_in_<table>s_table` — pour modifier une colonne
- `drop_<colonne>_from_<table>s_table` — pour supprimer une colonne

### 3. Implémenter la migration

La méthode `up()` doit toujours avoir une méthode `down()` correspondante et réversible :

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('<table>', function (Blueprint $table) {
            // Modifications ici
        });
    }

    public function down(): void
    {
        Schema::table('<table>', function (Blueprint $table) {
            // Annulation exacte de up()
        });
    }
};
```

### 4. Mettre à jour le modèle Eloquent si nécessaire

- Ajouter/retirer les colonnes de `$fillable`
- Ajouter les casts de types dans `$casts`
- Mettre à jour la Factory correspondante

### 5. Mettre à jour la documentation du schéma

Mettre à jour `docs/database/schema.md` avec les nouvelles colonnes.

### 6. Appliquer la migration

```bash
php artisan migrate
```

Vérifier le succès :
```bash
php artisan migrate:status
```

### 7. Lancer les tests après migration

```bash
./vendor/bin/pest
```

### 8. Commit

```bash
git add database/migrations/ app/Models/ docs/
git commit -m "feat(#<issue>): <description_de_la_migration>"
```
