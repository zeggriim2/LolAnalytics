# LolAnalytics

Application d'analyse de parties League of Legends. Ingère les données de match via l'API Riot Games et les expose via une interface d'administration.

**Stack** : Symfony 7.3 · PHP 8.4 · MySQL 8 · Vue 3 · TypeScript · Docker (FrankenPHP)

---

## Prérequis

- [Docker](https://docs.docker.com/get-docker/) + Docker Compose
- [Lefthook](https://github.com/evilmartians/lefthook) (pour les git hooks)

### Installer Lefthook

**macOS**
```bash
brew install lefthook
```

**Linux**
```bash
# Debian/Ubuntu
curl -1sLf 'https://dl.cloudsmith.io/public/evilmartians/lefthook/setup.deb.sh' | sudo bash
sudo apt-get install lefthook

# Script universel
curl -1sLf https://raw.githubusercontent.com/evilmartians/lefthook/master/install.sh | sudo bash
```

---

## Installation

```bash
# 1. Cloner le projet
git clone <repo-url>
cd LolAnalytics

# 2. Copier et configurer les variables d'environnement
cp .env .env.local
# Renseigner API_RIOT_TOKEN dans .env.local

# 3. Démarrer les conteneurs + installer les git hooks
make start

# 4. Lancer les migrations
make sf c=doctrine:migrations:migrate
```

`make start` exécute automatiquement `lefthook install` — les git hooks sont actifs dès cette étape.

---

## Commandes utiles

### Docker

```bash
make up           # Démarrer les conteneurs
make down         # Arrêter les conteneurs
make logs         # Suivre les logs
make bash         # Shell dans le conteneur PHP
make node-sh      # Shell dans le conteneur Node
```

### Symfony

```bash
make sf c=<commande>                        # Commande Symfony console
make sf c=doctrine:migrations:migrate       # Migrations
make sf c=doctrine:migrations:diff          # Générer une migration
make cc                                     # Vider le cache
```

### Tests

```bash
make test                    # Tous les tests
make test-unit               # Tests unitaires uniquement
make test-functional         # Tests fonctionnels uniquement
make test-coverage-text      # Couverture de code (terminal)
```

### Qualité de code

```bash
make qa                  # Tous les outils (PHP + Front)

# PHP
make cs-check            # Vérifier le style (dry-run)
make cs-fix              # Corriger le style
make phpstan             # Analyse statique (niveau 8)

# Frontend
make front-lint          # ESLint
make front-lint-fix      # ESLint avec correction automatique
make front-format-check  # Prettier (dry-run)
make front-format        # Prettier avec correction
make front-type-check    # TypeScript
```

### Frontend

```bash
make yarn-install        # Installer les dépendances Node
make yarn-dev            # Démarrer le serveur Vite (hot reload, port 5173)
make yarn-build          # Build de production
```

### Assets (images DataDragon)

```bash
# Télécharger les images champions pour une version
make sf c="app:champions:download-images 15.1.1"

# Télécharger les images items pour une version
make sf c="app:items:download-images 15.1.1"
```

---

## Git Hooks (Lefthook)

Les hooks sont configurés dans `lefthook.yml` et s'activent via `make install-hooks` (inclus dans `make start`).

### Pre-commit

Les vérifications suivantes s'exécutent **en parallèle** sur les fichiers stagés :

| Hook | Fichiers concernés | Commande |
|---|---|---|
| PHP CS Fixer | `src/**/*.php` | `make cs-check` |
| PHPStan | `src/**/*.php` | `make phpstan` |
| ESLint | `assets/**/*.{ts,vue,js}` | `make front-lint` |
| Prettier | `assets/**/*.{ts,vue,js,css}` | `make front-format-check` |

> Les hooks PHP utilisent l'image Docker `jakzal/phpqa` — pas besoin que les conteneurs soient démarrés.
> Les hooks frontend nécessitent que le conteneur Node soit en cours d'exécution (`make up`).

### Commit-msg

Le message de commit doit respecter les [Conventional Commits](https://www.conventionalcommits.org/) :

```
<type>(<scope>): <description>

Types : feat, fix, chore, docs, refactor, test, style, perf, ci, build, revert

Exemples :
  feat(match): ajouter le calcul du KDA
  fix(summoner): corriger l'import du PUUID
  chore: mettre à jour les dépendances
```

### Pre-push

Les tests unitaires sont lancés avant chaque `git push` :

```bash
make test-unit
```

### Post-merge

Après un `git pull` ou `git merge`, un avertissement s'affiche si `composer.lock` ou `yarn.lock` ont changé :

```
⚠️  composer.lock a changé — relance : make composer c=install
⚠️  yarn.lock a changé — relance : make yarn-install
```

**Bypasser les hooks** (à utiliser avec précaution) :
```bash
git commit --no-verify
```

**Réinstaller les hooks** (après un clone ou si supprimés) :
```bash
make install-hooks
```
