# o13sLife — Modèle de domaine

## Vision

o13sLife est une page web personnelle qui présente un CV de manière soignée et professionnelle. Elle est consultable publiquement par tous (recruteurs, contacts, etc.) et administrée via un back-office privé. Le contenu est multilingue (FR par défaut, EN, IT, ES) et géré via un système brouillon / publication.

---

## Entités du domaine

### Profil
Informations transversales affichées en en-tête de la page CV.

| Attribut | Type | Description |
|---|---|---|
| photo | image | Photo de profil |
| titre | string | Titre professionnel (ex : Développeur Full Stack) |
| email | string | Adresse email de contact |
| telephone | string | Numéro de téléphone (optionnel) |
| localisation | string | Ville / pays |
| lien_linkedin | url | Profil LinkedIn |
| lien_github | url | Profil GitHub |

### Expérience
Une expérience professionnelle dans le CV.

| Attribut | Type | Description |
|---|---|---|
| titre_poste | string | Intitulé du poste |
| entreprise | string | Nom de l'entreprise |
| date_debut | date | Date de début |
| date_fin | date\|null | Date de fin (null = poste actuel) |
| description | text | Description des missions |
| technologies | string[] | Liste des technologies utilisées |

**Tri** : par date de début décroissante.

### Formation
Un diplôme ou une certification obtenu.

| Attribut | Type | Description |
|---|---|---|
| ecole | string | Nom de l'établissement |
| annee | integer | Année d'obtention |
| diplome | string | Intitulé du diplôme |

### Compétence
Une compétence technique, groupée par catégorie.

| Attribut | Type | Description |
|---|---|---|
| categorie | string | Groupe (ex : Langages, Frameworks, Outils) |
| nom | string | Nom de la compétence |
| niveau | enum | `debutant` / `intermediaire` / `expert` |

### Langue
Une langue maîtrisée.

| Attribut | Type | Description |
|---|---|---|
| langue | string | Nom de la langue |
| niveau | string | Niveau libre (ex : Natif, Professionnel, DALF C2) |

### CentreInteret
Un centre d'intérêt personnel.

| Attribut | Type | Description |
|---|---|---|
| libelle | string | Mot ou courte expression |

### Message (formulaire de contact)
Un message envoyé via le formulaire de contact.

| Attribut | Type | Description |
|---|---|---|
| nom | string | Nom de l'expéditeur |
| email | string | Email de l'expéditeur |
| message | text | Corps du message |
| envoye_le | datetime | Date d'envoi |

---

## Relations

- Le **Profil** est unique (une seule instance).
- Un **Profil** peut avoir plusieurs **Expériences**, **Formations**, **Compétences**, **Langues**, **CentresInteret**.
- Les **Compétences** sont groupées par `categorie`.
- Les **Messages** sont indépendants du reste (formulaire de contact entrant).

---

## Règles métier et invariants

1. **Rubrique vide = rubrique masquée** : toute section sans contenu n'est pas affichée sur la page publique.
2. **Brouillon / Publication** : toute modification passe par un état `brouillon` avant d'être visible sur la page publique. La publication est un acte explicite.
3. **Accès back-office unique** : un seul compte administrateur, pas d'inscription publique.
4. **Expériences triées** : les expériences professionnelles sont toujours affichées par date de début décroissante.
5. **Multilingue** : toutes les chaînes de l'interface passent par le système de traduction Laravel. Les données saisies dans le back-office sont traduites manuellement par rubrique.

---

## Cycle de vie du contenu

```
[Brouillon] ──(publication)──▶ [Publié]
     ▲                              │
     └──────(modification)──────────┘
```

---

## Processus principaux

### Consultation publique
1. Le visiteur arrive sur la page CV publique.
2. Laravel affiche les rubriques dont l'état est `publié` et qui contiennent du contenu.
3. Le visiteur peut changer la langue via un sélecteur.
4. Le visiteur peut envoyer un message via le formulaire de contact.

### Gestion du contenu (back-office)
1. L'administrateur se connecte via login/mot de passe.
2. Il accède au back-office et modifie les rubriques (Profil, Expériences, Formations, etc.).
3. Les modifications sont enregistrées en `brouillon`.
4. L'administrateur publie explicitement pour rendre les changements visibles.

### Formulaire de contact
1. Le visiteur remplit et soumet le formulaire.
2. Un email est envoyé à l'administrateur (service email à définir lors de l'implémentation de cette feature).
3. Le message est stocké en base.

---

## Intégrations externes

| Service | Rôle | Statut |
|---|---|---|
| Service email | Envoi des messages du formulaire de contact | À définir (Mailgun, Resend, SMTP…) |

---

## Contraintes fonctionnelles

- **Performance** : pas d'exigence chiffrée définie (projet personnel).
- **Disponibilité** : pas de SLA formel.
- **Données** : RGPD applicable aux messages du formulaire de contact (données personnelles des expéditeurs).
- **Offline** : non requis.
- **Traçabilité** : logs Laravel standards suffisants.
