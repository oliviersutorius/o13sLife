# o13sLife — Routes

> Généré à partir de `routes/web.php`, `routes/console.php` et `bootstrap/app.php`. À maintenir à jour à chaque route ajoutée/modifiée (`/doc-update`).

## Page publique

| Méthode | URI | Nom | Controller | Accès |
|---|---|---|---|---|
| GET | `/` | `cv` | `CvController@index` | Visiteur (public) |

Affiche uniquement les rubriques `is_published = true`, avec leur contenu figé au dernier `published_snapshot` (cf. [`database/schema.md`](database/schema.md)). Aucune route de formulaire de contact n'existe à ce jour (Epic 6 non implémentée).

## Back-office (`/admin`)

Toutes les routes sont préfixées `admin/` et nommées `admin.*`.

### Publiques (middleware `guest`)

| Méthode | URI | Nom | Controller |
|---|---|---|---|
| GET | `admin/login` | `admin.login` | `Admin\AuthController@showLogin` |
| POST | `admin/login` | — | `Admin\AuthController@login` (+ `throttle:admin-login`) |

Le rate limiter `admin-login` (`app/Providers/AppServiceProvider.php`) limite à **5 tentatives/minute**, par clé `email + IP`, pour éviter qu'un attaquant ne bloque un autre compte en variant l'email tout en gardant l'IP.

### Protégées (middleware `auth`)

| Méthode | URI | Nom | Controller | Description |
|---|---|---|---|---|
| POST | `admin/logout` | `admin.logout` | `Admin\AuthController@logout` | |
| GET | `admin` | `admin.dashboard` | `Admin\DashboardController@index` | Tableau de bord |
| GET | `admin/profil` | `admin.profil.edit` | `Admin\ProfilController@edit` | Vue hébergeant le Livewire `ProfilForm` |
| GET | `admin/experiences` | `admin.experience.index` | `Admin\ExperienceController@index` | Vue hébergeant le Livewire `Experience\Index` (CRUD complet) |
| GET | `admin/formations` | `admin.formation.index` | `Admin\FormationController@index` | Vue hébergeant le Livewire `Formation\Index` (CRUD complet) |
| GET | `admin/competences` | `admin.competence.index` | `Admin\CompetenceController@index` | Vue hébergeant le Livewire `Competence\Index` (CRUD complet) |
| GET | `admin/langues` | `admin.langue.index` | `Admin\LangueController@index` | Vue hébergeant le Livewire `Langue\Index` (CRUD complet) |
| GET | `admin/centres-interet` | `admin.centre-interet.index` | `Admin\CentreInteretController@index` | Vue hébergeant le Livewire `CentreInteret\Index` (CRUD complet) |

Les controllers ci-dessus sont volontairement minces (ils ne font que retourner une vue) : la logique métier (création, édition, publication, dépublication, suppression, validation) est portée par les composants Livewire correspondants, pas par les controllers.

### Redirections globales (middleware `web`, `bootstrap/app.php`)

- Visiteur non authentifié sur une route protégée → redirigé vers `admin.login`
- Utilisateur déjà authentifié sur une route `guest` (ex : `admin.login`) → redirigé vers `admin.dashboard`
- Middleware `SetLocale` appliqué à **toutes** les routes web (public + back-office) : lit la locale depuis le cookie `locale`, retombe sur `fr` si absente ou invalide

## Health check

| Méthode | URI | Nom |
|---|---|---|
| GET | `/up` | — (Laravel health check standard) |

## Routes techniques (vendor, non listées en détail)

- `livewire-*/...` — assets JS/CSS et endpoints internes de Livewire (`update`, `upload-file`, `preview-file`)
- `storage/{path}` — accès aux fichiers du disque `public` (ex : photos de profil)

## Commandes Artisan applicatives

Hors scope HTTP mais font partie de la surface fonctionnelle du projet :

| Commande | Rôle |
|---|---|
| `php artisan admin:credentials {--email=} {--password=}` | Change l'email et/ou le mot de passe de l'administrateur, en local comme en prod (voir [`configuration.md`](configuration.md)) |
| `php artisan cv:translate-missing {locale?}` | Dispatche `TranslateContentJob` pour les rubriques dont une traduction est manquante dans la locale ciblée (ou toutes les locales non-FR par défaut) |
