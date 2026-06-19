# o13sLife — Workflow de développement

## Schéma du cycle complet

```
Issue GitHub créée
        │
        ▼
Branche feature/#issue-description créée depuis main
        │
        ▼
Développement local (commits type(#issue): desc)
        │
        ├── [pre-commit] → Pint (formatage) + vérification dd()/var_dump()
        ├── [post-commit] → Mise à jour changelog + documentation
        │
        ▼
Push de la branche feature
        │
        ├── [pre-push] → Pest (tests complets + couverture 95%) + Playwright + k6
        │
        ▼
Ouverture d'une PR vers main
        │
        ▼
Review N1 automatique (Agent Reviewer N1)
        │    ├── Bugs & régressions
        │    ├── Sécurité OWASP
        │    ├── Performance (N+1 Eloquent)
        │    ├── Conventions PSR-12 + commits
        │    ├── Couverture tests 95%
        │    ├── Documentation PHPDoc
        │    ├── Accessibilité WCAG 2.1 AA
        │    ├── Qualité UX / Tailwind
        │    └── Chaînes i18n manquantes
        │
        ▼
Commentaires inline sur la PR GitHub
        │
        ├── ❌ Problème BLOQUANT → Correction obligatoire → Nouveau push → Review N1 relancée
        │
        ▼
Review N2 humaine (toi) — obligatoire sur toutes les PRs
        │
        ▼
Squash merge dans main
        │
        ▼
GitHub Actions CI/CD déclenché sur main
        │    ├── Lint (Pint)
        │    ├── Tests (Pest --coverage --min=95)
        │    ├── Tests E2E (Playwright)
        │    ├── Tests de charge (k6)
        │    ├── Audit sécurité (composer audit)
        │    └── Build assets (Vite)
        │
        ▼
Déploiement production (manuel)
        │
        ▼
Issue GitHub fermée
```

---

## Checklist développeur avant d'ouvrir une PR

### Code

- [ ] Tous les fichiers PHP contiennent `declare(strict_types=1);`
- [ ] Pas de `dd()`, `var_dump()`, `dump()` oublié
- [ ] Formatage appliqué : `./vendor/bin/pint`
- [ ] PHPDoc sur toutes les méthodes et classes publiques

### Tests

- [ ] Tests unitaires écrits pour chaque méthode publique
- [ ] Happy path ET cas d'erreur couverts
- [ ] Couverture ≥ 95% : `./vendor/bin/pest --coverage --min=95`
- [ ] Tests E2E passants : `npx playwright test`

### i18n & Accessibilité

- [ ] Toutes les chaînes visibles via `__()` ou `@lang()`
- [ ] Fichiers `lang/fr/` et `lang/en/` à jour
- [ ] Attributs `aria-*` présents sur les éléments interactifs
- [ ] Classes Tailwind uniquement (pas de style inline)

### Git

- [ ] Chaque commit référence l'issue GitHub : `type(#issue): description`
- [ ] Branche nommée : `feature/#issue-description`
- [ ] Changelog mis à jour : `/changelog`
- [ ] Documentation à jour : `/doc-update`

---

## Checklist reviewer avant de merger

### Automated (Review N1)

- [ ] Aucun problème 🔴 BLOQUANT signalé
- [ ] Rapport de Review N1 disponible en commentaires inline

### Humain (Review N2)

- [ ] Le code fait bien ce que la PR décrit
- [ ] Pas de complexité inutile ou d'over-engineering
- [ ] L'implémentation respecte l'architecture du projet
- [ ] Les tests couvrent les vrais cas métier
- [ ] Pas de régression visible sur les fonctionnalités existantes

### Merge

- [ ] Stratégie : **Squash merge** uniquement
- [ ] Message du squash commit au format `type(#issue): description`
- [ ] Branche de feature supprimée après merge
- [ ] Issue GitHub fermée et liée à la PR

---

## Catalogue des agents et chaîne d'intervention

| Agent | Activation | Périmètre |
|---|---|---|
| **Architecte** | À la demande, sur nouvelles features structurantes | ADR, choix techniques, structure modules |
| **Développeur** | Sur chaque tâche de code | Implementation, refactoring, bug fixes |
| **Reviewer N1** | Automatique sur chaque push + `/review` | Review complète des 9 critères |
| **Testeur** | À la demande ou sur features complexes | Stratégie tests, Pest, Playwright, k6 |
| **Documentaliste** | Post-commit automatique + `/doc-update` | PHPDoc, docs/, CHANGELOG |
| **DevOps** | Sur changements CI/CD ou déploiement | GitHub Actions, hébergement |
| **Auditeur sécurité** | Sur PR + `/security-check` | OWASP, dépendances, secrets |
| **UX/Design** | Sur composants frontend | Design system, Tailwind, responsive |
| **i18n** | Sur ajout de vues ou chaînes | lang/fr, lang/en, couverture traductions |

**Chaîne :** Développeur → Reviewer N1 → Humain → merge
