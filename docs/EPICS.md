# o13sLife — Backlog fonctionnel (Epics)

## Ordre de priorité MVP

Les epics sont à développer dans cet ordre strict (chaque epic peut dépendre des précédentes).

---

## Epic 1 — Page CV publique

**Objectif** : Afficher le CV complet de manière soignée et responsive sur une page publique.
**Scope** : MVP
**Dépend de** : —

### User stories
- En tant que visiteur, je veux voir les expériences professionnelles triées par date décroissante afin d'évaluer rapidement le parcours.
- En tant que visiteur, je veux voir les formations, compétences, langues et centres d'intérêt afin d'avoir une vue complète du profil.
- En tant que visiteur, je veux que les rubriques sans contenu soient masquées afin de ne pas voir de sections vides.
- En tant que visiteur, je veux voir le profil (photo, titre, coordonnées, liens) en en-tête afin d'identifier immédiatement la personne.

### Critères d'acceptation
- [ ] Toutes les rubriques remplies s'affichent correctement
- [ ] Les rubriques vides sont masquées
- [ ] Les expériences sont triées par date décroissante
- [ ] Le design est responsive (mobile, tablette, desktop)
- [ ] WCAG 2.1 AA respecté

---

## Epic 2 — Authentification back-office

**Objectif** : Protéger l'accès au back-office par un login sécurisé.
**Scope** : MVP
**Dépend de** : Epic 1 (structure de l'app)

### User stories
- En tant qu'administrateur, je veux me connecter avec un email et un mot de passe afin d'accéder au back-office.
- En tant qu'administrateur, je veux me déconnecter afin de sécuriser ma session.
- En tant que visiteur non authentifié, je veux être redirigé vers la page de login si j'accède au back-office afin que le contenu admin soit protégé.

### Critères d'acceptation
- [ ] Login / logout fonctionnels
- [ ] Routes back-office protégées par middleware auth
- [ ] Pas d'inscription publique possible
- [ ] Session sécurisée (CSRF, expiration)

---

## Epic 3 — Back-office : gestion du Profil

**Objectif** : Permettre à l'administrateur de modifier les informations de son profil public (en-tête du CV).
**Scope** : MVP
**Dépend de** : Epic 2

### User stories
- En tant qu'administrateur, je veux modifier ma photo, mon titre, mes coordonnées et mes liens afin de maintenir mon profil à jour.
- En tant qu'administrateur, je veux prévisualiser les changements avant de les publier afin de contrôler ce qui est visible publiquement.

### Critères d'acceptation
- [ ] Formulaire d'édition du Profil fonctionnel
- [ ] Upload de photo fonctionnel
- [ ] Les modifications sont sauvegardées en brouillon
- [ ] La publication rend les changements visibles publiquement

---

## Epic 4 — Back-office : gestion des rubriques

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
- [ ] CRUD complet pour chaque rubrique (Expériences, Formations, Compétences, Langues, Centres d'intérêt)
- [ ] Validation des données à la saisie
- [ ] Les modifications sont sauvegardées en brouillon

---

## Epic 5 — Système brouillon / publication

**Objectif** : Permettre à l'administrateur de préparer des modifications sans les rendre immédiatement visibles, et de les publier en un acte explicite.
**Scope** : MVP
**Dépend de** : Epics 3 et 4

### User stories
- En tant qu'administrateur, je veux que mes modifications soient sauvegardées en brouillon afin de les préparer sans impacter la page publique.
- En tant qu'administrateur, je veux publier mes modifications d'un seul clic afin de mettre à jour la page publique quand je suis prêt.
- En tant qu'administrateur, je veux voir clairement ce qui est en brouillon et ce qui est publié afin de savoir l'état de mes contenus.

### Critères d'acceptation
- [ ] Chaque rubrique a un état `brouillon` / `publié`
- [ ] La page publique n'affiche que le contenu `publié`
- [ ] Le back-office indique visuellement l'état de chaque rubrique
- [ ] Le bouton "Publier" déclenche la mise à jour de la page publique

---

## Epic 6 — Formulaire de contact

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

## Epic 7 — Multilingue (FR / EN / IT / ES)

**Objectif** : Permettre aux visiteurs de consulter le CV dans leur langue.
**Scope** : MVP
**Dépend de** : Epic 1

### User stories
- En tant que visiteur, je veux choisir la langue d'affichage (FR, EN, IT, ES) afin de lire le CV dans ma langue.
- En tant qu'administrateur, je veux saisir le contenu de chaque rubrique dans plusieurs langues afin de proposer un CV adapté à chaque audience.

### Critères d'acceptation
- [ ] Sélecteur de langue visible sur la page publique
- [ ] Interface traduite en FR, EN, IT, ES
- [ ] Contenu des rubriques traduisible par langue dans le back-office
- [ ] Langue par défaut : français
- [ ] URL ou cookie pour mémoriser la langue choisie

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
