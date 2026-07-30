# o13sLife — Backlog fonctionnel (Epics)

## Ordre de priorité MVP

Les epics sont à développer dans cet ordre strict (chaque epic peut dépendre des précédentes).

> **Statut mis à jour le 2026-07-30** à partir de l'état réel du code (routes, controllers, composants Livewire, migrations). Voir aussi [`docs/routes.md`](routes.md) et [`docs/database/schema.md`](database/schema.md).

---

## Epic 1 — Page CV publique

**Statut : ✅ Implémentée** (`feat(#3)`/`#11`)

**Objectif** : Afficher le CV complet de manière soignée et responsive sur une page publique.
**Scope** : MVP
**Dépend de** : —

### User stories
- En tant que visiteur, je veux voir les expériences professionnelles triées par date décroissante afin d'évaluer rapidement le parcours.
- En tant que visiteur, je veux voir les formations, compétences, langues et centres d'intérêt afin d'avoir une vue complète du profil.
- En tant que visiteur, je veux que les rubriques sans contenu soient masquées afin de ne pas voir de sections vides.
- En tant que visiteur, je veux voir le profil (photo, titre, coordonnées, liens) en en-tête afin d'identifier immédiatement la personne.

### Critères d'acceptation
- [x] Toutes les rubriques remplies s'affichent correctement (`CvController@index`, `resources/views/cv.blade.php`)
- [x] Les rubriques vides sont masquées (`@if($experiences->isNotEmpty())` etc. dans `cv.blade.php`)
- [x] Les expériences sont triées par date décroissante (`CvController@index` → `sortByDesc('date_debut')`)
- [x] Le design est responsive (classes Tailwind `sm:`/`md:`/`lg:` dans `cv.blade.php`)
- [ ] WCAG 2.1 AA respecté — attributs `aria-*` présents dans les vues, mais **aucun test automatisé (axe ou équivalent)** ne vérifie formellement la conformité AA ; à valider manuellement ou outiller

---

## Epic 2 — Authentification back-office

**Statut : ✅ Implémentée** (`feat(#4)`/`#12`, durcie par `fix(#28)`)

**Objectif** : Protéger l'accès au back-office par un login sécurisé.
**Scope** : MVP
**Dépend de** : Epic 1 (structure de l'app)

### User stories
- En tant qu'administrateur, je veux me connecter avec un email et un mot de passe afin d'accéder au back-office.
- En tant qu'administrateur, je veux me déconnecter afin de sécuriser ma session.
- En tant que visiteur non authentifié, je veux être redirigé vers la page de login si j'accède au back-office afin que le contenu admin soit protégé.

### Critères d'acceptation
- [x] Login / logout fonctionnels (`Admin\AuthController@login`/`@logout`)
- [x] Routes back-office protégées par middleware `auth` (`routes/web.php`)
- [x] Pas d'inscription publique possible (aucune route register ; seul `AdminUserSeeder`/`admin:credentials` créent ou modifient l'admin, cf. [`configuration.md`](configuration.md))
- [x] Session sécurisée (`@csrf` sur le formulaire de login, `SESSION_LIFETIME=120`, rate limiting 5 tentatives/min par email+IP sur `admin-login`)

---

## Epic 3 — Back-office : gestion du Profil

**Statut : ✅ Implémentée** (`feat(#5)`/`#13`)

**Objectif** : Permettre à l'administrateur de modifier les informations de son profil public (en-tête du CV).
**Scope** : MVP
**Dépend de** : Epic 2

### User stories
- En tant qu'administrateur, je veux modifier ma photo, mon titre, mes coordonnées et mes liens afin de maintenir mon profil à jour.
- En tant qu'administrateur, je veux prévisualiser les changements avant de les publier afin de contrôler ce qui est visible publiquement.

### Critères d'acceptation
- [x] Formulaire d'édition du Profil fonctionnel (`Livewire\Admin\ProfilForm`)
- [x] Upload de photo fonctionnel (`WithFileUploads`, stockage sur le disque `public`, ancienne photo supprimée sauf si encore référencée par `published_snapshot`)
- [x] Les modifications sont sauvegardées en brouillon (`sauvegarder()` sans publier)
- [x] La publication rend les changements visibles publiquement (`publier()` → `Profil::publish()`)
- [ ] ~~Prévisualiser les changements avant de les publier~~ (user story) — pas de vue de prévisualisation dédiée ; le brouillon reste visible uniquement dans le formulaire back-office, sans rendu "tel que vu par un visiteur"

---

## Epic 4 — Back-office : gestion des rubriques

**Statut : ✅ Implémentée** (`feat(#6)`/`#14`) — hors réordonnancement

**Objectif** : Permettre à l'administrateur de créer, modifier, réordonner et supprimer les entrées de chaque rubrique du CV.
**Scope** : MVP
**Dépend de** : Epic 2

### User stories
- En tant qu'administrateur, je veux ajouter, modifier et supprimer des expériences professionnelles afin de maintenir mon parcours à jour.
- En tant qu'administrateur, je veux ajouter, modifier et supprimer des formations afin de maintenir mon parcours académique à jour.
- En tant qu'administrateur, je veux gérer mes compétences par catégorie avec leur niveau afin de présenter mes savoir-faire clairement.
- En tant qu'administrateur, je veux gérer mes langues avec leur niveau afin de montrer ma maîtrise linguistique.
- En tant qu'administrateur, je veux gérer mes centres d'intérêt afin de donner un aperçu de ma personnalité.

### Critères d'acceptation
- [x] CRUD complet pour chaque rubrique (Expériences, Formations, Compétences, Langues, Centres d'intérêt) — composants Livewire `Index` dans `app/Livewire/Admin/{Rubrique}/Index.php`, mêmes méthodes `creer`/`editer`/`sauvegarder`/`togglePublication`/`supprimer` pour les 5 rubriques
- [x] Validation des données à la saisie (attributs `#[Validate(...)]` sur chaque composant)
- [x] Les modifications sont sauvegardées en brouillon (création via `is_published: false`, publication explicite via `togglePublication`)
- [ ] ~~Réordonner~~ (user story) — non implémenté : les listes sont triées automatiquement par un critère fixe (ex. date pour les expériences), pas de réordonnancement manuel par l'administrateur

---

## Epic 5 — Système brouillon / publication

**Statut : ✅ Implémentée** (`fix(#26)`/`#27` — snapshot JSON après une première implémentation incomplète)

**Objectif** : Permettre à l'administrateur de préparer des modifications sans les rendre immédiatement visibles, et de les publier en un acte explicite.
**Scope** : MVP
**Dépend de** : Epics 3 et 4

### User stories
- En tant qu'administrateur, je veux que mes modifications soient sauvegardées en brouillon afin de les préparer sans impacter la page publique.
- En tant qu'administrateur, je veux publier mes modifications d'un seul clic afin de mettre à jour la page publique quand je suis prêt.
- En tant qu'administrateur, je veux voir clairement ce qui est en brouillon et ce qui est publié afin de savoir l'état de mes contenus.

### Critères d'acceptation
- [x] Chaque rubrique a un état `brouillon` / `publié` (colonne `is_published`, trait `HasPublishedSnapshot`)
- [x] La page publique n'affiche que le contenu `publié` (scope `published()` + `withPublishedContent()` figé au snapshot, `CvController@index`)
- [x] Le back-office indique visuellement l'état de chaque rubrique (badge publié/brouillon dans les vues `livewire/admin/*/index.blade.php`)
- [x] Le bouton "Publier" déclenche la mise à jour de la page publique (`togglePublication()` → `Model::publish()`)

---

## Epic 6 — Formulaire de contact

**Statut : ❌ Non implémentée** — aucune route, controller, modèle ou migration `messages` dans le code actuel.

**Objectif** : Permettre aux visiteurs d'envoyer un message à l'administrateur depuis la page CV.
**Scope** : MVP
**Dépend de** : Epic 1

### User stories
- En tant que visiteur, je veux envoyer un message via un formulaire afin de contacter le propriétaire du CV.
- En tant qu'administrateur, je veux recevoir un email à chaque nouveau message afin d'être notifié rapidement.

### Critères d'acceptation
- [ ] Formulaire avec champs nom, email, message
- [ ] Validation des données (email valide, champs requis)
- [ ] Protection anti-spam (rate limiting ou captcha)
- [ ] Message stocké en base de données
- [ ] Email de notification envoyé à l'administrateur
- [ ] ⚠️ **Service email à choisir avant l'implémentation** : Mailgun, Resend, SMTP ? À valider avec l'administrateur.

---

## Epic 7 — Multilingue (FR / EN / IT / ES / DE)

**Statut : ✅ Implémentée** (`feat(#9)`/`#15`, `#18`, durcie par `fix(#16)`/`#17`) — **le périmètre réel couvre aussi l'allemand (DE)**, ajouté par l'issue [#19](https://github.com/oliviersutorius/o13sLife/issues/19)/[#20](https://github.com/oliviersutorius/o13sLife/issues/20), non reflété dans le titre original de cette epic ni dans `docs/DOMAIN.md`

**Objectif** : Permettre aux visiteurs de consulter le CV dans leur langue.
**Scope** : MVP
**Dépend de** : Epic 1

### User stories
- En tant que visiteur, je veux choisir la langue d'affichage (FR, EN, IT, ES) afin de lire le CV dans ma langue.
- En tant qu'administrateur, je veux saisir le contenu de chaque rubrique dans plusieurs langues afin de proposer un CV adapté à chaque audience.

### Critères d'acceptation
- [x] Sélecteur de langue visible sur la page publique (`Livewire\LocaleSwitcher`, drapeaux SVG depuis `fix(#24)`)
- [x] Interface traduite en FR, EN, IT, ES, **et DE** (`lang/{fr,en,it,es,de}/`)
- [x] Contenu des rubriques traduisible par langue dans le back-office (`spatie/laravel-translatable`, colonnes JSON, traduction automatique via `TranslateContentJob`/Claude Haiku + relecture manuelle via `TranslationBadges`, statuts `validated`/`auto`/`missing`)
- [x] Langue par défaut : français (`SetLocale::DEFAULT_LOCALE`)
- [x] Cookie pour mémoriser la langue choisie (`SetLocale` middleware lit/écrit le cookie `locale`)

---

## Epic 8 — Plusieurs pages CV (post-MVP)

**Objectif** : Permettre de créer plusieurs versions du CV pour différentes cibles (ex : CV tech, CV management).
**Scope** : Post-MVP
**Dépend de** : Toutes les epics MVP

### User stories
- En tant qu'administrateur, je veux créer plusieurs versions de mon CV afin d'adapter ma présentation selon la cible.
- En tant que visiteur, je veux accéder à la bonne version du CV via une URL dédiée.

### Critères d'acceptation
- [ ] À définir lors de l'implémentation

---

## Dépendances entre epics

```
Epic 1 (Page publique)
    │
    ├──▶ Epic 2 (Auth) ──▶ Epic 3 (Profil) ──▶ Epic 5 (Brouillon/Publication)
    │                  └──▶ Epic 4 (Rubriques) ─────────────────────────────▶ Epic 5
    │
    ├──▶ Epic 6 (Contact)
    └──▶ Epic 7 (Multilingue)
                                        Epic 8 (Post-MVP, dépend de tout)
```
