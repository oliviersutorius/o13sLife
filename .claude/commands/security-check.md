# /security-check — Audit de sécurité

Tu es l'agent **Auditeur sécurité** du projet o13sLife. Lance un audit de sécurité complet sur les dépendances et le code du projet.

## Étapes

### 1. Audit des dépendances PHP

```bash
composer audit
```

Analyser la sortie :
- Lister les CVE identifiées avec leur sévérité (critical / high / medium / low)
- Proposer la mise à jour pour chaque dépendance vulnérable
- Bloquer si une CVE **critical** ou **high** est trouvée sur Laravel, PHP, ou SQLite

### 2. Vérification des secrets exposés

Scanner le code pour des patterns suspects :

```bash
# Tokens, clés API, mots de passe codés en dur
grep -rn --include="*.php" --include="*.blade.php" \
  -E "(password|secret|token|api_key|private_key)\s*=\s*['\"][^'\"]{8,}" \
  app/ resources/ config/ \
  | grep -v ".env"
```

```bash
# Vérifier que .env n'est pas tracké par Git
git check-ignore -v .env
```

### 3. Vérification OWASP Top 10 — Laravel

**Injection SQL**
```bash
grep -rn --include="*.php" "DB::statement\|DB::raw\|whereRaw\|selectRaw\|orderByRaw" app/
```
Chaque occurrence doit utiliser des bindings paramétrés.

**XSS**
```bash
grep -rn --include="*.blade.php" "{!!" resources/views/
```
Chaque `{!! !!}` doit être justifié et la valeur doit être sanitisée.

**Mass Assignment**
```bash
grep -rn --include="*.php" "\$fillable\|\$guarded" app/Models/
```
Vérifier que `$guarded = []` n'est pas utilisé sans précaution.

**CSRF**
```bash
grep -rn --include="*.blade.php" "<form" resources/views/ | head -20
```
Chaque formulaire POST doit contenir `@csrf`.

### 4. Vérifications de configuration

```bash
# Vérifier que APP_DEBUG est false en production
grep "APP_DEBUG" .env.example

# Vérifier que APP_KEY est définie
php artisan key:generate --show 2>/dev/null | head -1
```

### 5. Rapport de sortie

Produire un rapport structuré :

```
## Rapport de sécurité o13sLife — <date>

### Dépendances
- ✅ / ❌ composer audit : <résultat>

### Secrets exposés
- ✅ / ❌ Aucun secret détecté en dur dans le code

### OWASP Top 10
- ✅ / ⚠️ Injection SQL : <résultat>
- ✅ / ⚠️ XSS : <résultat>
- ✅ / ⚠️ Mass Assignment : <résultat>
- ✅ / ⚠️ CSRF : <résultat>

### Configuration
- ✅ / ⚠️ APP_DEBUG : <résultat>

### Décision
❌ BLOQUANT — corriger avant tout merge
✅ AUCUN PROBLÈME CRITIQUE DÉTECTÉ
```
