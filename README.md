# E-Intendance LISI — Application de Gestion Budgétaire

Application web de gestion budgétaire destinée au laboratoire LISI de l'Université Cadi Ayyad de Marrakech.
Elle permet à l'administrateur de piloter les budgets annuels et aux émetteurs de soumettre leurs expressions de besoins,
avec validation, suivi des reliquats et exports officiels (DOCX / Excel / PDF).

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Backend | Laravel 11 / PHP 8.2 |
| Base de données | MySQL 8 |
| Frontend | Alpine.js / TailwindCSS |
| Exports | PhpWord · DomPDF · Maatwebsite Excel |

---

## Prérequis

- PHP 8.2+
- Composer
- MySQL 8
- Node.js 18+

---

## Installation

```bash
git clone https://github.com/moi-brooks/lisi-budget.git
cd lisi-budget
composer install
cp .env.example .env
php artisan migrate --seed
npm run build
```

Configurer `.env` avec les paramètres de base de données avant le migrate.

---

## Lancer l'application

```bash
php artisan serve
```

Accéder à : [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Déploiement Railway

Le dépôt contient déjà un `Procfile` et un modèle `.env.railway.example`.

### 1. Connecter le repo GitHub à Railway

1. Créer un compte sur [railway.app](https://railway.app) puis **New Project → Deploy from GitHub repo**.
2. Sélectionner le dépôt `lisi-budget`. Railway détecte automatiquement le projet Laravel (Nixpacks) et lance un premier build.
3. Le `Procfile` définit la commande de démarrage :
   `web: php artisan serve --host=0.0.0.0 --port=$PORT`

### 2. Ajouter un service MySQL

1. Dans le projet Railway : **New → Database → Add MySQL**.
2. Railway provisionne une base et expose les variables `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`.

### 3. Configurer les variables d'environnement

Dans le service de l'application → onglet **Variables**, reporter le contenu de `.env.railway.example`.
Pour les variables `DB_`, utiliser les *reference variables* du service MySQL :

| Variable        | Valeur                        |
|-----------------|-------------------------------|
| `DB_CONNECTION` | `mysql`                       |
| `DB_HOST`       | `${{MySQL.MYSQLHOST}}`        |
| `DB_PORT`       | `${{MySQL.MYSQLPORT}}`        |
| `DB_DATABASE`   | `${{MySQL.MYSQLDATABASE}}`    |
| `DB_USERNAME`   | `${{MySQL.MYSQLUSER}}`        |
| `DB_PASSWORD`   | `${{MySQL.MYSQLPASSWORD}}`    |

Ne pas oublier :
- `APP_KEY` — générer en local avec `php artisan key:generate --show` puis coller la valeur.
- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<domaine-railway>`.
- Extensions PHP requises pour les exports : **`gd`** (PDF / DomPDF) et **`zip`** (DOCX & Excel).
  Sur Nixpacks : `NIXPACKS_PHP_EXTENSIONS=gd,zip` (variable d'environnement du service).

### 4. Lancer les migrations depuis Railway

Après le premier déploiement, ouvrir un shell Railway sur le service de l'app
(**⋮ → Terminal**, ou `railway run` en local avec la CLI) et exécuter :

```bash
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

`--seed` crée l'admin, les lignes budgétaires de base et un jeu de données de démonstration.
Pour un environnement vierge, lancer `php artisan migrate --force` sans `--seed`.

---

## Identifiants par défaut

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | `admin@uca.ac.ma` | `password` |
| Émetteur | `alami@uca.ac.ma` | `password` |

---

## Rôles

**Admin** — Gère les budgets, lignes budgétaires, émetteurs. Valide les propositions et expressions de besoins. Génère les exports officiels.

**Émetteur** — Soumet des propositions budgétaires et des expressions de besoins. Suit l'état de ses demandes et le reliquat disponible.

---

## Encadrant

**Pr. ESSAID EL BACHARI** — Faculté des Sciences Semlalia — Université Cadi Ayyad, Marrakech

## Réalisé par

**ADHAM EL WARARI** et **EL KAABI OTHMANE**

Université Cadi Ayyad — Faculté des Sciences Semlalia — 2025/2026
