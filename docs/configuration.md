# o13sLife — Configuration

> Variables d'environnement utilisées par l'application, au-delà du strict boilerplate Laravel. Référence : `.env.example`. À maintenir à jour à chaque nouvelle variable introduite (`/doc-update`).

## Variables spécifiques au projet

### Compte administrateur (`ADMIN_EMAIL`, `ADMIN_PASSWORD`)

| Variable | Obligatoire | Défaut | Description |
|---|---|---|---|
| `ADMIN_EMAIL` | non | `admin@o13slife.local` | Email du compte admin créé par `AdminUserSeeder` |
| `ADMIN_PASSWORD` | non | mot de passe aléatoire généré | Mot de passe du compte admin créé par `AdminUserSeeder` |

Ces variables ne sont lues **qu'à la toute première exécution** du seeder (`AdminUserSeeder::run()` s'arrête immédiatement si un utilisateur existe déjà — invariant "un seul administrateur"). Les modifier après coup n'a aucun effet sur un compte déjà créé.

Si `ADMIN_PASSWORD` est absent, un mot de passe aléatoire fort est généré et écrit dans `storage/app/private/admin-generated-password.txt` (fichier non versionné, permissions `0600`) — il n'est jamais affiché en clair dans les logs.

**Pour changer les identifiants d'un admin déjà créé** (local ou prod), utiliser la commande dédiée plutôt que de modifier ces variables :

```bash
php artisan admin:credentials --email=nouveau@example.com --password=un-nouveau-mot-de-passe
```

Voir aussi [`docs/routes.md`](routes.md) et l'issue [#32](https://github.com/oliviersutorius/o13sLife/issues/32).

### Traduction automatique (Claude Haiku)

`App\Services\TranslationService` (utilisé par `TranslateContentJob` et la commande `cv:translate-missing`) appelle l'API Anthropic via le package `mozex/anthropic-laravel` (facade `Anthropic\Laravel\Facades\Anthropic`, requis en dépendance de production depuis l'issue [#36](https://github.com/oliviersutorius/o13sLife/issues/36)).

| Variable | Obligatoire | Défaut | Description |
|---|---|---|---|
| `ANTHROPIC_API_KEY` | oui | — | Clé API Anthropic (`config/anthropic.php` → `anthropic.api_key`) |
| `ANTHROPIC_REQUEST_TIMEOUT` | non | `30` (secondes) | Timeout des requêtes vers l'API |
| `ANTHROPIC_BETA` | non | — | Liste de features beta séparées par des virgules, envoyées sur chaque requête |

Sans `ANTHROPIC_API_KEY`, toute tentative de traduction (job `TranslateContentJob`, commande `cv:translate-missing`) échoue. La configuration est publiée dans `config/anthropic.php` (`php artisan vendor:publish --provider="Anthropic\Laravel\ServiceProvider"`).

## Variables Laravel standard pertinentes pour ce projet

| Variable | Valeur projet | Remarque |
|---|---|---|
| `APP_LOCALE` / `APP_FALLBACK_LOCALE` | `en` (config Laravel) | Ne pas confondre avec la locale d'affichage du CV, gérée séparément par cookie `locale` + middleware `SetLocale` (défaut `fr`, cf. [`routes.md`](routes.md)) |
| `APP_DEBUG` | `false` | Ne jamais passer à `true` en production (cf. issue #28) |
| `DB_CONNECTION` / `DB_DATABASE` | `sqlite` / `database/database.sqlite` | Base unique, pas de config hôte/port/identifiants |
| `SESSION_DRIVER` | `database` | Sessions back-office |
| `QUEUE_CONNECTION` | `database` | File utilisée par `TranslateContentJob` — nécessite un worker (`php artisan queue:listen`, déjà présent dans le script composer `dev`) |
| `CACHE_STORE` | `database` | |
| `BCRYPT_ROUNDS` | `12` | Coût du hash des mots de passe (`4` en environnement de test, cf. `phpunit.xml`) |

## Locales supportées

Le CV public est disponible en **5 locales** (et non 4 comme indiqué historiquement dans certains documents de conception) : `fr` (défaut), `en`, `it`, `es`, `de` — voir `App\Http\Middleware\SetLocale::LOCALES` et le dossier `lang/`. ⚠️ [`docs/DOMAIN.md`](DOMAIN.md) et le `CLAUDE.md` mentionnent encore uniquement FR/EN/IT/ES ; l'ajout de l'allemand (issue [#19](https://github.com/oliviersutorius/o13sLife/issues/19)/[#20](https://github.com/oliviersutorius/o13sLife/issues/20)) n'y a pas été répercuté.

## Fichiers protégés

Conformément au `CLAUDE.md`, `.env`/`.env.*` ne sont ni lisibles-modifiables ni créables par Claude Code au-delà d'un template vide — toute nouvelle variable ajoutée à `.env.example` doit être proposée puis appliquée manuellement par l'administrateur du projet si l'édition automatique est bloquée par `.claude/settings.json`.
