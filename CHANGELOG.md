# Changelog

Toutes les modifications notables de ce projet sont documentées ici.
Format basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/).

---

## [Unreleased] — 2026-07-30

> Première entrée du changelog, reconstituée rétroactivement à partir de l'historique Git complet du projet (issue [#34](https://github.com/oliviersutorius/o13sLife/issues/34)).

### ✨ Nouvelles fonctionnalités
- feat(#3): page CV publique avec toutes les rubriques ([#3](https://github.com/oliviersutorius/o13sLife/issues/3))
- feat(#4): authentification back-office ([#4](https://github.com/oliviersutorius/o13sLife/issues/4))
- feat(#5): back-office — gestion du Profil ([#5](https://github.com/oliviersutorius/o13sLife/issues/5))
- feat(#6): back-office — gestion des rubriques ([#6](https://github.com/oliviersutorius/o13sLife/issues/6))
- feat(#9): scaffold multilingue — spatie/translatable, LocaleSwitcher, TranslationBadges ([#9](https://github.com/oliviersutorius/o13sLife/issues/9))
- feat(#9): formulaire de relecture des traductions avec statuts 3 états ([#9](https://github.com/oliviersutorius/o13sLife/issues/9))
- feat(#19): ajouter la locale DE (allemand) ([#19](https://github.com/oliviersutorius/o13sLife/issues/19))
- feat(#19): commande artisan `cv:translate-missing` pour traduire le contenu existant ([#19](https://github.com/oliviersutorius/o13sLife/issues/19))

### 🐛 Corrections de bugs
- fix(#16): dette technique multilingue — sécurité, performance, i18n, tests ([#16](https://github.com/oliviersutorius/o13sLife/issues/16))
- fix(#24): drapeaux SVG au lieu d'emojis dans le sélecteur de langue ([#24](https://github.com/oliviersutorius/o13sLife/issues/24))
- fix(#26): sépare réellement brouillon et publié via un snapshot JSON ([#26](https://github.com/oliviersutorius/o13sLife/issues/26))
- fix(#28): rate limiting login admin fiable, `APP_DEBUG=false`, `npm audit fix` ([#28](https://github.com/oliviersutorius/o13sLife/issues/28))
- fix(#32): identifiants admin configurables via `ADMIN_EMAIL`/`ADMIN_PASSWORD`, ajoute la commande `admin:credentials` ([#32](https://github.com/oliviersutorius/o13sLife/issues/32))

### ⚡ Améliorations de performance
- perf(#28): élimine le N+1 sur le dashboard admin et ajoute un index sur `is_published` ([#28](https://github.com/oliviersutorius/o13sLife/issues/28))

### 🧪 Tests
- test(#22): couverture Pest 95% — tests manquants ajoutés ([#22](https://github.com/oliviersutorius/o13sLife/issues/22))
- test(#28): couverture des 3 lacunes identifiées par l'audit de sécurité ([#28](https://github.com/oliviersutorius/o13sLife/issues/28))

### 🔧 Maintenance
- chore: initialiser le harnais Claude Code (configuration, agents, hooks)
- chore(#1): initialiser le projet Laravel 13 avec la stack complète ([#1](https://github.com/oliviersutorius/o13sLife/issues/1))
