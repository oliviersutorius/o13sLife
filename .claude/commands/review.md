# /review — Review Niveau 1 (Agent automatique)

Tu es l'agent **Reviewer N1** du projet o13sLife. Lance une analyse complète des fichiers modifiés depuis `main` et produis un rapport de review sous forme de commentaires inline sur la PR GitHub active.

## Étapes

### 1. Identifier les fichiers modifiés

```bash
git diff main...HEAD --name-only
```

### 2. Analyser chaque fichier selon les 9 critères suivants

**1. Bugs et régressions**
- Logique incorrecte, conditions inversées, edge cases non gérés
- Valeurs nulles non anticipées, variables non initialisées
- Boucles infinies potentielles

**2. Sécurité OWASP Top 10**
- Injections SQL (utiliser les query builders Eloquent, jamais de requêtes raw non paramétrées)
- XSS (échappement Blade `{{ }}` vs `{!! !!}`)
- Secrets ou tokens exposés dans le code
- Validation des entrées utilisateur manquante
- CSRF (vérifier `@csrf` dans les formulaires)

**3. Performance**
- Requêtes N+1 Eloquent (utiliser `with()` pour les relations)
- Complexité algorithmique excessive (O(n²) ou pire dans des boucles)
- Requêtes non nécessaires en base de données

**4. Conventions du projet**
- PSR-12 strict
- `declare(strict_types=1);` présent dans chaque fichier PHP
- Pas de `dd()`, `var_dump()`, `dump()` oublié
- Chaînes i18n : toutes les chaînes visibles passent par `__()` ou `@lang()`

**5. Couverture de tests**
- Chaque nouvelle méthode publique a un test Pest correspondant
- Les cas d'erreur sont testés (pas seulement le happy path)
- Seuil de couverture 95% respecté

**6. Documentation**
- PHPDoc manquant sur les méthodes et classes publiques
- README ou docs à mettre à jour si nouvelle feature

**7. Accessibilité WCAG 2.1 AA**
- Attributs `aria-*` présents sur les éléments interactifs
- Textes alternatifs sur les images (`alt=""`)
- Contraste des couleurs suffisant
- Navigation clavier possible

**8. Qualité UX / Design**
- Cohérence avec le design system Tailwind du projet
- Responsive (mobile-first vérifié)
- Pas de CSS inline ou `<style>` custom non justifié

**9. i18n**
- Aucune chaîne codée en dur dans les vues Blade ou composants Livewire
- Fichiers `lang/fr/` et `lang/en/` à jour
- Clés de traduction cohérentes et organisées

### 3. Format du rapport

Pour chaque problème détecté, poster un commentaire inline sur la PR avec ce format :

```
🔴 [BLOQUANT] Fichier.php:42
Problème : Description précise du problème
Suggestion : Code ou approche recommandée

🟡 [AVERTISSEMENT] Fichier.php:87
Problème : Description précise
Suggestion : Recommandation

🟢 [SUGGESTION] Fichier.php:103
Problème : Amélioration possible
Suggestion : Recommandation
```

### 4. Décision finale

Poster un commentaire de synthèse sur la PR :

- Si au moins un 🔴 **BLOQUANT** : `❌ MERGE BLOQUÉ — X problème(s) bloquant(s) à corriger`
- Si uniquement 🟡 et 🟢 : `✅ MERGE AUTORISÉ après review humaine — X avertissement(s), Y suggestion(s)`
- Si aucun problème : `✅ MERGE AUTORISÉ après review humaine — Aucun problème détecté`

Utilise le MCP GitHub pour poster les commentaires inline et le commentaire de synthèse sur la PR.
