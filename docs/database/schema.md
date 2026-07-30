# o13sLife — Schéma de base de données

> Généré à partir des migrations réelles (`database/migrations/`). À maintenir à jour à chaque nouvelle migration (`/doc-update`).

Base de données : **SQLite** (`database/database.sqlite`).

---

## Tables du domaine CV

Toutes les tables ci-dessous partagent le même mécanisme brouillon/publication (trait `App\Models\Concerns\HasPublishedSnapshot`) :

- `is_published` (`boolean`, défaut `false`, **indexé**) — état courant de la rubrique
- `published_snapshot` (`json`, nullable) — copie figée des attributs publiables au moment du dernier appel à `publish()` ; c'est ce contenu qui est affiché sur la page publique, jamais le contenu brouillon en cours d'édition
- `translations_validated` (`json`, nullable) — liste des clés `champ.locale` dont la traduction a été validée manuellement par l'administrateur (sinon considérée comme traduction automatique)

Les colonnes marquées **🌍 traduisible** sont stockées en `json` (une valeur par locale FR/EN/IT/ES/DE) via `spatie/laravel-translatable`.

### `profils`

Rubrique **Profil** (unique — un seul enregistrement).

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `photo` | string | oui | Chemin du fichier sur le disque `public` |
| `titre` | json 🌍 | non | Titre professionnel |
| `email` | string | non | Email de contact affiché publiquement |
| `telephone` | string | oui | |
| `localisation` | string | oui | Ville / pays |
| `lien_linkedin` | string | oui | URL |
| `lien_github` | string | oui | URL |
| `is_published` | boolean | — | défaut `false`, indexé |
| `published_snapshot` | json | oui | |
| `translations_validated` | json | oui | |
| `created_at` / `updated_at` | timestamp | — | |

### `experiences`

Rubrique **Expérience**. Triées côté application par `date_debut` décroissant.

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `titre_poste` | json 🌍 | oui | |
| `entreprise` | string | non | |
| `date_debut` | date | non | |
| `date_fin` | date | oui | `null` = poste actuel |
| `description` | json 🌍 | oui | |
| `technologies` | json | oui | Liste de chaînes |
| `is_published` | boolean | — | défaut `false`, indexé |
| `published_snapshot` | json | oui | |
| `translations_validated` | json | oui | |
| `created_at` / `updated_at` | timestamp | — | |

### `formations`

Rubrique **Formation**.

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `ecole` | string | non | |
| `annee` | unsigned smallint | non | |
| `diplome` | json 🌍 | oui | |
| `is_published` | boolean | — | défaut `false`, indexé |
| `published_snapshot` | json | oui | |
| `translations_validated` | json | oui | |
| `created_at` / `updated_at` | timestamp | — | |

### `competences`

Rubrique **Compétence**, groupées par `categorie` côté affichage.

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `categorie` | json 🌍 | non | Groupe (ex : Langages, Frameworks) |
| `nom` | json 🌍 | non | |
| `niveau` | enum | non | `debutant` \| `intermediaire` \| `expert` (non traduisible) |
| `is_published` | boolean | — | défaut `false`, indexé |
| `published_snapshot` | json | oui | |
| `translations_validated` | json | oui | |
| `created_at` / `updated_at` | timestamp | — | |

### `langues`

Rubrique **Langue** (langue maîtrisée par l'administrateur, ≠ locale d'interface).

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `langue` | string | non | Non traduisible (nom propre de la langue) |
| `niveau` | json 🌍 | non | Niveau libre (ex : Natif, Professionnel, DALF C2) |
| `is_published` | boolean | — | défaut `false`, indexé |
| `published_snapshot` | json | oui | |
| `translations_validated` | json | oui | |
| `created_at` / `updated_at` | timestamp | — | |

### `centres_interet`

Rubrique **CentreInteret**.

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `libelle` | json 🌍 | non | |
| `is_published` | boolean | — | défaut `false`, indexé |
| `published_snapshot` | json | oui | |
| `translations_validated` | json | oui | |
| `created_at` / `updated_at` | timestamp | — | |

---

## Table d'authentification

### `users`

Un seul enregistrement en usage normal (règle métier : accès back-office unique, pas d'inscription publique). Créé par `AdminUserSeeder` (voir [`configuration.md`](../configuration.md)).

| Colonne | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint (PK) | — | |
| `name` | string | non | |
| `email` | string (unique) | non | |
| `email_verified_at` | timestamp | oui | Non utilisé actuellement (pas de vérification email) |
| `password` | string (hashed) | non | |
| `remember_token` | string | oui | |
| `created_at` / `updated_at` | timestamp | — | |

---

## Tables techniques (Laravel par défaut)

Non spécifiques au domaine o13sLife, générées par le squelette Laravel :

| Table | Rôle |
|---|---|
| `password_reset_tokens` | Réinitialisation de mot de passe (non exposée dans l'UI actuellement) |
| `sessions` | Sessions HTTP (`SESSION_DRIVER=database`) |
| `cache`, `cache_locks` | Cache applicatif (`CACHE_STORE=database`) |
| `jobs`, `job_batches`, `failed_jobs` | File d'attente (`QUEUE_CONNECTION=database`), utilisée par `TranslateContentJob` |

---

## Non implémenté

- **Table `messages`** (formulaire de contact, Epic 6 de [`EPICS.md`](../EPICS.md)) — décrite dans [`DOMAIN.md`](../DOMAIN.md) mais aucune migration ni modèle ne l'implémente à ce jour.
