# o13sLife

Page web personnelle présentant un CV de manière soignée et professionnelle. Consultable publiquement (recruteurs, contacts…) et administrée via un back-office privé. Contenu multilingue (FR par défaut, EN, IT, ES, DE) géré via un système brouillon / publication : aucune modification n'est visible sur la page publique tant qu'elle n'a pas été explicitement publiée.

Pour la documentation complète, voir [`docs/`](docs) :

| Document | Contenu |
|---|---|
| [`docs/DOMAIN.md`](docs/DOMAIN.md) | Modèle de domaine, entités, règles métier |
| [`docs/EPICS.md`](docs/EPICS.md) | Backlog fonctionnel et statut d'implémentation |
| [`docs/GLOSSARY.md`](docs/GLOSSARY.md) | Glossaire des termes métier |
| [`docs/routes.md`](docs/routes.md) | Routes HTTP et commandes Artisan |
| [`docs/database/schema.md`](docs/database/schema.md) | Schéma de base de données |
| [`docs/configuration.md`](docs/configuration.md) | Variables d'environnement |
| [`docs/WORKFLOW.md`](docs/WORKFLOW.md) | Workflow de développement (Git, hooks, agents) |
| [`CHANGELOG.md`](CHANGELOG.md) | Historique des changements |

## Stack technique

| Couche | Technologie |
|---|---|
| Langage | PHP 8.3 |
| Framework | Laravel 13 |
| Frontend réactif | Livewire 4 + Alpine.js |
| CSS | TailwindCSS |
| Base de données | SQLite |
| Tests unitaires / intégration | Pest PHP |
| Tests E2E | Playwright |
| Traduction automatique | API Anthropic (Claude Haiku) |

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```

Le premier `migrate --seed` crée le compte administrateur (`AdminUserSeeder`). Voir [`docs/configuration.md`](docs/configuration.md) pour configurer `ADMIN_EMAIL`/`ADMIN_PASSWORD`, et la commande `php artisan admin:credentials` pour les modifier ensuite.

## Développement

```bash
# Démarrer serveur + queue worker + logs + Vite en parallèle
composer dev

# Ou séparément :
php artisan serve
npm run dev

# Tests
./vendor/bin/pest
./vendor/bin/pest --coverage --min=95
npx playwright test

# Qualité
./vendor/bin/pint
composer audit
```

Voir [`CLAUDE.md`](CLAUDE.md) pour les conventions de code, le workflow Git et le catalogue des agents utilisés sur ce projet.
