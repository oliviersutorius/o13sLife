# o13sLife — Glossaire

## Termes du domaine

### Profil
**Définition** : Bloc d'informations transversales affiché en en-tête de la page CV (photo, titre professionnel, coordonnées, liens). Il est unique — il n'existe qu'une seule instance de Profil.
**Synonymes acceptés** : en-tête, header CV.
**À éviter** : "utilisateur" (désigne le compte admin, pas le Profil public).

---

### Rubrique
**Définition** : Une section thématique du CV (Expériences, Formations, Compétences, Langues, Centres d'intérêt). Une rubrique sans contenu n'est pas affichée.
**Synonymes acceptés** : section.
**À éviter** : "module", "bloc" (termes trop génériques).

---

### Expérience
**Définition** : Une expérience professionnelle avec titre de poste, entreprise, dates, description et technologies. Triée par date de début décroissante.
**Synonymes acceptés** : expérience professionnelle, poste.

---

### Formation
**Définition** : Un diplôme ou certification avec école, année et intitulé du diplôme.
**Synonymes acceptés** : diplôme, cursus.

---

### Compétence
**Définition** : Une compétence technique appartenant à une catégorie (ex : Langages) avec un niveau débutant / intermédiaire / expert.
**À éviter** : "skill" (utiliser "compétence" dans le code et les traductions FR).

---

### Langue
**Définition** : Une langue maîtrisée avec un niveau exprimé librement (Natif, Professionnel, DALF C2…). Ne pas confondre avec la langue d'affichage de l'interface.
**Distinction importante** : "Langue" (entité du CV) ≠ "locale" (langue d'affichage de l'interface).

---

### Centre d'intérêt
**Définition** : Un mot ou une courte expression décrivant un intérêt personnel. Affiché sous forme de liste ou de tags.
**Synonymes acceptés** : intérêt, hobby.

---

### Brouillon
**Définition** : État d'un contenu modifié mais pas encore visible sur la page publique. Tout changement commence en brouillon.
**Synonymes acceptés** : draft.
**Distinction** : "brouillon" ≠ "publié" — les deux états sont mutuellement exclusifs.

---

### Publication
**Définition** : Acte explicite de l'administrateur qui rend le contenu en brouillon visible sur la page publique.
**Synonymes acceptés** : publier, mettre en ligne.

---

### Back-office
**Définition** : Interface d'administration privée, accessible uniquement à l'administrateur connecté, permettant de gérer toutes les rubriques du CV.
**Synonymes acceptés** : admin, espace d'administration.
**À éviter** : "dashboard" (trop générique).

---

### Administrateur
**Définition** : L'unique compte utilisateur ayant accès au back-office. C'est le propriétaire du CV.
**Distinction** : "administrateur" (compte back-office) ≠ "visiteur" (personne qui consulte la page publique).

---

### Visiteur
**Définition** : Toute personne consultant la page CV publique sans être connectée. Peut envoyer un message via le formulaire de contact.
**Synonymes acceptés** : lecteur, recruteur (dans le contexte métier).

---

### Message
**Définition** : Données soumises via le formulaire de contact (nom, email, message). Stockées en base et transmises par email à l'administrateur.

---

### Locale
**Définition** : La langue d'affichage de l'interface sélectionnée par le visiteur (fr, en, it, es). Ne pas confondre avec l'entité "Langue" du CV.
**Langues supportées** : `fr` (défaut), `en`, `it`, `es`.
