# /changelog — Mise à jour du changelog

Tu es l'agent **Documentaliste** du projet o13sLife. Mets à jour le fichier `CHANGELOG.md` à partir des commits depuis la dernière entrée.

## Étapes

### 1. Récupérer les commits depuis la dernière entrée du changelog

```bash
# Identifier le dernier tag ou la dernière date d'entrée
git log --oneline --no-merges $(git describe --tags --abbrev=0 2>/dev/null || echo "")..HEAD
```

Si aucun tag n'existe encore :
```bash
git log --oneline --no-merges HEAD
```

### 2. Grouper les commits par type

Regrouper selon les préfixes Conventional Commits :

| Préfixe | Section changelog |
|---|---|
| `feat` | ✨ Nouvelles fonctionnalités |
| `fix` | 🐛 Corrections de bugs |
| `perf` | ⚡ Améliorations de performance |
| `refactor` | ♻️ Refactoring |
| `docs` | 📚 Documentation |
| `test` | 🧪 Tests |
| `chore` | 🔧 Maintenance |
| `style` | 💄 Style / formatage |

### 3. Format d'une entrée changelog

```markdown
## [Unreleased] — YYYY-MM-DD

### ✨ Nouvelles fonctionnalités
- feat(#12): description de la feature ([#12](lien-issue))

### 🐛 Corrections de bugs
- fix(#15): description du correctif ([#15](lien-issue))

### 🔧 Maintenance
- chore(#8): description de la tâche ([#8](lien-issue))
```

### 4. Créer ou mettre à jour `CHANGELOG.md`

Si `CHANGELOG.md` n'existe pas, le créer avec l'en-tête :

```markdown
# Changelog

Toutes les modifications notables de ce projet sont documentées ici.
Format basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/).

---
```

Insérer la nouvelle entrée **au début**, après l'en-tête.

### 5. Commit de mise à jour

```bash
git add CHANGELOG.md
git commit -m "docs(#<issue>): mettre à jour le changelog"
```
