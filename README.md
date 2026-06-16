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

**Pr. ESSAID EL BACHARI** — Université Cadi Ayyad, Marrakech

## Réalisé par

**ADHAM EL WARARI** et **EL KAABI OTHMANE**

Université Cadi Ayyad — Faculté des Sciences Semlalia — 2025/2026
