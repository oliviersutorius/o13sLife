# /doc-update — Mise à jour de la documentation

Tu es l'agent **Documentaliste** du projet o13sLife. Analyse les fichiers modifiés depuis le dernier commit et mets à jour la documentation technique correspondante.

## Étapes

### 1. Identifier les fichiers modifiés

```bash
git diff HEAD~1 --name-only
```

### 2. Analyser l'impact sur la documentation

Pour chaque fichier modifié, déterminer si une documentation doit être créée ou mise à jour :

| Fichier modifié | Documentation à mettre à jour |
|---|---|
| `app/Models/*.php` | PHPDoc du modèle + `docs/models/<Nom>.md` si complexe |
| `app/Livewire/**/*.php` | PHPDoc des méthodes publiques |
| `database/migrations/*.php` | `docs/database/schema.md` |
| `routes/*.php` | `docs/routes.md` |
| `config/*.php` | `docs/configuration.md` |
| `lang/**/*.php` | Vérifier cohérence fr/en |

### 3. PHPDoc à vérifier

Chaque classe et méthode publique doit avoir :

```php
/**
 * Description courte en une ligne.
 *
 * @param  Type  $param  Description du paramètre
 * @return Type  Description du retour
 */
```

### 4. Mettre à jour `docs/` si nécessaire

- `docs/database/schema.md` — si une migration a été ajoutée
- `docs/routes.md` — si des routes ont été modifiées
- `docs/configuration.md` — si des options de config ont changé

### 5. Vérifier la cohérence i18n

```bash
# Lister les clés présentes en FR mais absentes en EN (et vice versa)
php artisan lang:check 2>/dev/null || echo "Package laravel-lang non installé"
```

Sinon, comparer manuellement les fichiers `lang/fr/` et `lang/en/` pour détecter les clés manquantes.

### 6. Commit

```bash
git add docs/ lang/ app/
git commit -m "docs: mettre à jour la documentation suite aux dernières modifications"
```
