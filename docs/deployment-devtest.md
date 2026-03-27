# Déploiement DevTest — LolAnalytics

Déploiement automatisé via GitHub Actions → GitHub Container Registry → API Portainer sur VPS Hostinger.

---

## Architecture

```
git push origin develop
        │
        ▼
┌─────────────────────────────────┐
│  GitHub Actions: CD - DevTest   │
│                                 │
│  Job 1 — build-push             │
│  ├─ docker build (multi-stage)  │
│  │   ├─ node_build              │
│  │   │   ├─ yarn install        │
│  │   │   └─ yarn build          │
│  │   └─ frankenphp_prod         │
│  │       ├─ composer install    │
│  │       ├─ COPY sources        │
│  │       └─ COPY public/build/  │◄─ depuis node_build
│  └─ push → ghcr.io/…:devtest-SHA│
│                                 │
│  Job 2 — deploy (env: devtest)  │
│  └─ curl PUT /api/stacks/{id}   │
│      └─ Portainer API (HTTPS)   │
└─────────────────────────────────┘
        │
        ▼
┌─────────────────────────────────┐
│  VPS Hostinger                  │
│  Portainer Stack: lolanalytics  │
│  ├─ php  (FrankenPHP + Caddy)   │
│  │   └─ JWT keys décodés        │
│  │      depuis env vars B64     │
│  └─ database (MySQL 8.4)        │
└─────────────────────────────────┘
```

---

## Prérequis

- VPS Hostinger avec Docker + Portainer installés
- Portainer accessible sur `https://<ip>:9443` (port 9443 ouvert dans le firewall)
- Repo GitHub avec branch `develop`

---

## Setup initial (une seule fois)

### 1. Ouvrir le port Portainer dans le firewall Hostinger

**hPanel → VPS → Firewall** : autoriser TCP entrant sur le port `9443`.

### 2. Créer un API Token Portainer

**Portainer → Avatar → My account → Access tokens → Add access token**

- Nom : `github-actions-devtest`
- Conserver le token affiché (valeur pour `PORTAINER_API_KEY`)

### 3. Trouver l'Endpoint ID

Dans Portainer, aller dans **Environments**. L'ID est visible dans l'URL : `/#/endpoints/1/...` → c'est `1`.

### 4. Configurer le registre GHCR dans Portainer

**Portainer → Settings → Registries → Add registry → Custom registry**

| Champ | Valeur |
|-------|--------|
| Registry URL | `ghcr.io` |
| Username | ton login GitHub |
| Password | PAT GitHub avec scope `read:packages` |

### 5. Créer l'Environment GitHub `devtest`

**Repo GitHub → Settings → Environments → New environment** → nom : `devtest`

#### Secrets à configurer dans l'environment `devtest`

| Secret                        | Description | Exemple |
|-------------------------------|-------------|---------|
| `PORTAINER_URL`               | URL Portainer | `https://1.2.3.4:9443` |
| `PORTAINER_API_KEY`           | Token créé à l'étape 2 | `ptr_xxx...` |
| `PORTAINER_ENDPOINT_ID`       | ID de l'environnement Docker | `1` |
| `DEVTEST_APP_SECRET`          | Symfony app secret | `openssl rand -hex 32` |
| `DEVTEST_DATABASE_URL`        | URL MySQL complète | `mysql://app:pass@database:3306/lolanalytics_devtest?serverVersion=8.4.8&charset=utf8mb4` |
| `DEVTEST_MYSQL_ROOT_PASSWORD` | Mot de passe root MySQL | — |
| `DEVTEST_MYSQL_DATABASE`      | Nom de la base | `lolanalytics_devtest` |
| `DEVTEST_MYSQL_USER`          | Utilisateur MySQL | `app` |
| `DEVTEST_MYSQL_PASSWORD`      | Mot de passe MySQL (= celui dans DATABASE_URL) | — |
| `DEVTEST_API_RIOT_KEY`        | Token Riot Games API | — |
| `DEVTEST_MERCURE_JWT_SECRET`  | Secret Mercure | `openssl rand -hex 32` |
| `DEVTEST_SERVER_NAME_PUBLIC`  | Domaine ou IP du VPS | `devtest.mondomaine.com` |
| `DEVTEST_JWT_PASSPHRASE`      | Passphrase des clés JWT | (depuis `.env.dev.local`) |

---

## Déclencher un déploiement

### Automatique

Tout `push` sur la branche `develop` déclenche le workflow `CD - DevTest`.

```bash
git push origin develop
```

### Manuel (sans push)

**GitHub → Actions → CD - DevTest → Run workflow → Branch: develop → Run workflow**

---

## Fichiers clés

| Fichier | Rôle |
|---------|------|
| `compose.devtest.yaml` | Stack Docker Compose pour le devtest (image GHCR + MySQL) |
| `.github/workflows/cd-devtest.yml` | Workflow GitHub Actions (build + deploy) |
| `Dockerfile` | Multi-stage : `node_build` (assets) + `frankenphp_prod` (PHP) |
| `frankenphp/docker-entrypoint.sh` | Décode les clés JWT depuis les env vars B64 au démarrage |

---

## Ce qui se passe au démarrage du container PHP

1. Attend que MySQL soit prêt (60 tentatives, 1s d'intervalle)
2. Exécute les migrations Doctrine (`doctrine:migrations:migrate`)
3. Génère le keypair JWT si absent (`lexik:jwt:generate-keypair`) — persisté dans le volume `jwt_keys`
4. Démarre FrankenPHP

---

## Stack Portainer

Après le premier déploiement, la stack `lolanalytics-devtest` est visible dans **Portainer → Stacks**.

Les redeployments suivants mettent à jour la stack automatiquement via l'API Portainer (`PUT /api/stacks/{id}`).

> **Note** : chaque déploiement utilise un tag d'image immuable (`:devtest-XXXXXXXX`) pour forcer Portainer à puller la nouvelle image à chaque fois.

---

## Créer un utilisateur admin

### Via Portainer (console du container)

**Portainer → Containers → lolanalytics-devtest-php-1 → Console → Connect**

```bash
bin/console auth:create-admin email@exemple.com MotDePasse
```

### Via SSH

```bash
ssh user@ton-vps \
  "docker exec lolanalytics-devtest-php-1 bin/console auth:create-admin email@exemple.com MotDePasse"
```

---

## Troubleshooting

### Container PHP en boucle de redémarrage

```bash
# Voir les logs du container
# Portainer → Containers → lolanalytics-devtest-php-1 → Logs
```

Causes fréquentes :
- `Environment variable not found` → vérifier que la variable est dans `.env` avec une valeur par défaut
- `Connection refused` (MySQL) → MySQL pas encore prêt, le container va réessayer automatiquement

### Vérifier que le déploiement a bien fonctionné

```bash
# Status des containers
ssh user@ton-vps "docker compose -f /opt/lolanalytics-devtest/compose.devtest.yaml ps"

# Logs PHP en temps réel
ssh user@ton-vps "docker compose -f /opt/lolanalytics-devtest/compose.devtest.yaml logs php -f --tail=50"
```

### Déclencher un redéploiement forcé sans modifier le code

**GitHub → Actions → CD - DevTest → Run workflow**

---

## Accès à l'application

| Service | URL |
|---------|-----|
| Application | `https://<SERVER_NAME>:8443` |
| Admin | `https://<SERVER_NAME>:8443/admin` |
| Portainer | `https://<SERVER_NAME>:9443` |
