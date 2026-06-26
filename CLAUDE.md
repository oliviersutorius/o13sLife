# o13sLife — Claude Code Configuration

## Contexte domaine

**o13sLife** est une page web personnelle qui présente un CV de manière soignée et professionnelle. Elle est consultable publiquement par tous (recruteurs, contacts) et administrée via un back-office privé. Le contenu est multilingue (FR par défaut, EN, IT, ES) et géré via un système brouillon / publication.

### Entités principales

| Entité | Description |
|---|---|
| **Profil** | En-tête du CV : photo, titre professionnel, coordonnées, liens LinkedIn/GitHub. Unique. |
| **Expérience** | Poste occupé : titre, entreprise, dates, description, technologies. Triées par date décroissante. |
| **Formation** | Diplôme : école, année, intitulé. |
| **Compétence** | Savoir-faire technique groupé par catégorie, avec niveau débutant/intermédiaire/expert. |
| **Langue** | Langue maîtrisée avec niveau libre (Natif, Professionnel, DALF C2…). |
| **CentreInteret** | Mot ou courte expression décrivant un intérêt personnel. |
| **Message** | Message envoyé via le formulaire de contact (nom, email, message). |

### Règles métier critiques

1. **Rubrique vide = rubrique masquée** — toute section sans contenu n'est pas affichée sur la page publique.
2. **Brouillon / Publication** — toute modification est en brouillon jusqu'à publication explicite.
3. **Accès back-office unique** — un seul compte administrateur, pas d'inscription publique.

### Types d'utilisateurs

| Rôle | Description |
|---|---|
| **Administrateur** | Propriétaire du CV, seul accès au back-office. |
| **Visiteur** | Toute personne consultant la page publique (recruteur, contact…). |

### Glossaire condensé

| Terme | Définition courte |
|---|---|
| Rubrique | Section thématique du CV (Expériences, Formations…) |
| Brouillon | Contenu modifié non encore visible publiquement |
| Publication | Acte rendant le brouillon visible sur la page publique |
| Locale | Langue d'affichage de l'interface (fr, en, it, es) — ≠ entité Langue du CV |
| Back-office | Interface d'administration privée |
| Visiteur | Personne consultant la page publique sans être connectée |

> Référence complète : `docs/DOMAIN.md`, `docs/GLOSSARY.md`, `docs/EPICS.md`

---

## Projet

**o13sLife** est une application web personnelle, développée en solo, rendue côté serveur avec PHP/Laravel.

### Stack technique

| Couche | Technologie |
|---|---|
| Langage | PHP 8.3 |
| Framework | Laravel 11 |
| Frontend réactif | Livewire 3 + Alpine.js |
| CSS | TailwindCSS |
| Base de données | SQLite |
| Tests unitaires / intégration | Pest PHP |
| Tests E2E + visuels | Playwright |
| Tests de performance / charge | k6 |
| CI/CD | GitHub Actions |

### Architecture

- Monorepo, greenfield
- Rendu serveur (SSR) via Blade + Livewire
- Pas de SPA, pas d'API REST publique
- SQLite stocké dans `database/database.sqlite`
- Hébergement à définir (Railway ou Fly.io recommandé pour SQLite + volumes persistants)

---

## Conventions de code

### PHP / Laravel

- Standard **PSR-12** strict
- Formatage : **Laravel Pint** — `./vendor/bin/pint`
- `declare(strict_types=1);` obligatoire dans chaque fichier PHP
- Interdit en production : `var_dump()`, `dd()`, `dump()`
- Les migrations ne se modifient jamais après création — toujours créer une nouvelle migration

### Commits

Format obligatoire : `type(#issue): description en minuscules`

| Type | Usage |
|---|---|
| `feat` | Nouvelle fonctionnalité |
| `fix` | Correction de bug |
| `chore` | Tâche de maintenance (deps, config) |
| `docs` | Documentation uniquement |
| `test` | Ajout ou modification de tests |
| `refactor` | Refactoring sans changement de comportement |
| `style` | Formatage, espaces, virgules |
| `perf` | Optimisation de performance |

Exemple valide : `feat(#12): ajouter le composant de profil utilisateur`

Chaque commit doit référencer une issue GitHub active.

### Branches

- Format : `feature/#issue-description-courte`
- Exemple : `feature/#12-profil-utilisateur`
- Une branche = une issue = une PR
- Branche de base : toujours `main`

### CSS / TailwindCSS

- Classes Tailwind exclusivement — pas de CSS inline ni de `<style>` custom
- Respecter la palette et l'échelle typographique de `tailwind.config.js`

### i18n

- Toutes les chaînes visibles passent par `__()` ou `@lang()`
- Fichiers de traduction : `lang/fr/` (langue par défaut) et `lang/en/`
- Aucune chaîne codée en dur dans les vues Blade

### Accessibilité

- Standard WCAG 2.1 AA minimum
- Attributs `aria-*` obligatoires sur les éléments interactifs
- Navigation clavier fonctionnelle sur tous les composants

---

## Commandes de développement

```bash
# Démarrer le serveur de développement
php artisan serve

# Compiler les assets (Vite)
npm run dev

# Lancer tous les tests
./vendor/bin/pest

# Lancer les tests avec couverture (seuil 95%)
./vendor/bin/pest --coverage --min=95

# Formater le code
./vendor/bin/pint

# Créer une migration
php artisan make:migration nom_de_la_migration

# Appliquer les migrations
php artisan migrate

# Tests E2E et visuels
npx playwright test

# Tests de performance
k6 run tests/performance/load.js

# Audit de sécurité des dépendances
composer audit

# Vérifier le statut des migrations
php artisan migrate:status
```

---

## Catalogue des agents

| Agent | Rôle | Périmètre |
|---|---|---|
| **Architecte** | Valide les choix techniques, rédige les ADR | Décisions structurantes, nouveaux modules, choix de dépendances |
| **Développeur** | Implémentation feature, bug fix, tests unitaires | Features, correctifs, composants Livewire |
| **Reviewer N1** | Review automatique avant merge | Chaque push sur feature branch + `/review` |
| **Testeur** | Écriture et maintenance des tests | Pest, Playwright, k6 — stratégie et couverture |
| **Documentaliste** | Génère et met à jour la doc technique | Post-commit automatique, `/doc-update` |
| **DevOps** | CI/CD, déploiement, monitoring | GitHub Actions, hébergement, alertes |
| **Auditeur sécurité** | Audit OWASP, secrets, dépendances | `composer audit`, Dependabot, `/security-check` |
| **UX/Design** | Cohérence design, accessibilité WCAG | Composants Livewire, Tailwind, responsive |
| **i18n** | Chaînes de traduction, couverture i18n | Vues Blade, composants, fichiers `lang/` |

**Chaîne d'intervention :** Développeur → Reviewer N1 → Humain → merge

---

## Workflow Git

1. Créer une issue GitHub avec le titre de la tâche
2. Créer une branche `feature/#issue-description-courte` depuis `main`
3. Développer avec des commits au format `type(#issue): description`
4. Pousser la branche et ouvrir une PR vers `main`
5. Le Reviewer N1 s'exécute automatiquement et commente inline sur la PR
6. Review humaine obligatoire (toutes les PRs, sans exception)
7. Merge en **squash** uniquement après les deux validations

---

## Ce que Claude Code peut faire

- Créer, modifier, supprimer des fichiers dans le projet
- Exécuter : `php artisan make:*`, `php artisan migrate`, `php artisan serve`, `php artisan route:list`
- Exécuter : `composer install/update/require/audit`
- Exécuter : `./vendor/bin/pest`, `./vendor/bin/pint`
- Exécuter : `npm run *`, `npx playwright *`, `k6 run *`
- Créer de nouvelles migrations (jamais modifier les existantes)
- Interagir avec GitHub via MCP (issues, PRs, labels, commentaires)
- Lire tous les fichiers de configuration

## Ce que Claude Code ne peut PAS faire

- Modifier ou créer `.env`, `.env.*`, `.env.example` (sauf template vide)
- Exécuter `rm -rf` ou toute suppression récursive forcée
- Exécuter `php artisan migrate:fresh`, `migrate:reset`, `migrate:refresh`
- Exécuter des requêtes SQL contenant `DROP TABLE`, `DROP DATABASE`, `TRUNCATE`
- Modifier des migrations existantes (après leur première exécution)
- Pousser directement sur `main` sans PR
- Merger une PR sans validation humaine

---

## Fichiers protégés

> Compléter dans `.claude/settings.json` → `permissions.deny` au fil du projet

Protections actuelles :
- `.env`, `.env.*`
- Migrations existantes (lecture autorisée, modification interdite)
- `config/` (lecture seule en production)

---

## Git hooks locaux

Activer les hooks du projet après le clone :

```bash
git config core.hooksPath .githooks
```
