# Laravel App — Corrections

Projet Laravel 13 regroupant deux exercices distincts, chacun identifié par son commit Git.

| Exercice | Commit | Message |
|----------|--------|---------|
| **Exercice 1** — API REST Tasks | [`c14b900`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/c14b900) | `feat: enhance README and implement task management API with CRUD operations` |
| **Exercice 2** — Composants Blade UI | [`ff6d457`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/ff6d457) | `feat: add reusable UI components and demo page` |

> Commit initial du projet : `6c12af0` — `feat: first commit with implementation of basic api task`

---

## Sommaire

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Exercice 1 — API REST Tasks](#exercice-1--api-rest-tasks)
  - [Commandes (Exo 1)](#commandes-exo-1)
  - [Endpoints API](#endpoints-api)
  - [Fichiers du commit `c14b900`](#fichiers-du-commit-c14b900)
- [Exercice 2 — Composants Blade UI](#exercice-2--composants-blade-ui)
  - [Commandes (Exo 2)](#commandes-exo-2)
  - [Page de démonstration](#page-de-démonstration)
  - [Fichiers du commit `ff6d457`](#fichiers-du-commit-ff6d457)
- [Exemples de requêtes API](#exemples-de-requêtes-api)
- [Stack technique](#stack-technique)

---

## Prérequis

- PHP **8.3+**
- Composer
- Extension PHP `sqlite3`

---

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=TaskSeeder   # Exo 1 — optionnel
php artisan serve
```

| Ressource | URL |
|-----------|-----|
| API Tasks | `http://127.0.0.1:8000/api/tasks` |
| Démo composants (Exo 2) | `http://127.0.0.1:8000/components-demo` |

---

# Exercice 1 — API REST Tasks

> **Commit :** `c14b900` — `feat: enhance README and implement task management API with CRUD operations`

Application d'une **API REST CRUD** pour gérer des tâches (`tasks`).  
Base de données **SQLite**, réponses JSON automatiques sur les routes `/api/*`.

## Commandes (Exo 1)

| Commande | Rôle |
|----------|------|
| `composer create-project laravel/laravel .` | Initialisation du projet Laravel |
| `composer install` | Installation des dépendances PHP |
| `cp .env.example .env` | Copie du fichier de configuration |
| `php artisan key:generate` | Génération de `APP_KEY` |
| `touch database/database.sqlite` | Création du fichier base SQLite |
| `php artisan make:model Task -m` | Modèle Eloquent + migration `tasks` |
| `php artisan make:controller TaskController --api --model=Task` | Contrôleur API resource |
| `php artisan make:request StoreTaskRequest` | Validation à la création |
| `php artisan make:request UpdateTaskRequest` | Validation à la mise à jour |
| `php artisan migrate` | Application des migrations en base |
| `php artisan make:seeder TaskSeeder` | Seeder de données de test |
| `php artisan db:seed --class=TaskSeeder` | Insertion des tâches de démo |
| `php artisan serve` | Serveur HTTP local (port 8000) |
| `php artisan route:list --path=api` | Liste des routes API enregistrées |

## Endpoints API

| Méthode | URL | Action | Code HTTP |
|---------|-----|--------|-----------|
| `GET` | `/api/tasks` | Liste paginée (filtre `?status=todo\|in_progress\|done`) | 200 |
| `POST` | `/api/tasks` | Créer une tâche | 201 |
| `GET` | `/api/tasks/{id}` | Afficher une tâche | 200 |
| `PUT` / `PATCH` | `/api/tasks/{id}` | Mettre à jour une tâche | 200 |
| `DELETE` | `/api/tasks/{id}` | Supprimer une tâche | 204 |

### Schéma d'une tâche

| Champ | Type | Obligatoire | Valeurs / contraintes |
|-------|------|-------------|------------------------|
| `title` | string | Oui (création) | max 255 caractères |
| `description` | string | Non | texte libre |
| `status` | enum | Non (défaut : `todo`) | `todo`, `in_progress`, `done` |
| `due_date` | date | Non | format date, doit être **après aujourd'hui** |

## Fichiers du commit `c14b900`

### `routes/api.php`

Enregistre une **route resource API** (`Route::apiResource`) pour le contrôleur `TaskController`. Laravel génère automatiquement les 5 routes REST sous le préfixe `/api`.

---

### `bootstrap/app.php`

Configuration de l'application Laravel 13 :

- **Routing** : charge `routes/api.php` en plus des routes web et console.
- **Exceptions** : force le rendu JSON des erreurs pour toutes les requêtes `api/*` (422 validation, 404, etc.).

---

### `database/migrations/2026_06_01_084509_create_tasks_table.php`

Migration créant la table `tasks` :

- `id` — clé primaire auto-incrémentée
- `title` — titre obligatoire (string)
- `description` — texte optionnel
- `status` — enum (`todo`, `in_progress`, `done`), défaut `todo`
- `due_date` — date d'échéance optionnelle
- `created_at` / `updated_at` — timestamps Laravel

---

### `app/Models/Task.php`

Modèle Eloquent représentant une tâche :

- `$fillable` — champs assignables en masse (`title`, `description`, `status`, `due_date`)
- `$casts` — cast de `due_date` en objet `Carbon`

---

### `app/Http/Requests/StoreTaskRequest.php`

Form Request pour la **création** (`POST /api/tasks`) :

- `title` : requis, string, max 255
- `description` : optionnel
- `status` : optionnel, `todo | in_progress | done`
- `due_date` : optionnel, date postérieure à aujourd'hui

---

### `app/Http/Requests/UpdateTaskRequest.php`

Form Request pour la **mise à jour** (`PUT/PATCH /api/tasks/{id}`) :

- Mêmes règles que `StoreTaskRequest`, avec `sometimes|required` sur `title`

---

### `app/Http/Controllers/TaskController.php`

Contrôleur API resource :

| Méthode | Comportement |
|---------|--------------|
| `index()` | Liste paginée (10/page), filtrable par `?status=` |
| `store()` | Crée une tâche, retourne JSON **201 Created** |
| `show()` | Retourne une tâche via **route model binding** |
| `update()` | Met à jour et retourne la tâche modifiée |
| `destroy()` | Supprime la tâche, retourne **204 No Content** |

---

### `database/seeders/TaskSeeder.php`

Insère 3 tâches de démonstration :

1. « Configurer la base SQLite » — statut `done`
2. « Implémenter le CRUD API » — statut `in_progress`, échéance J+7
3. « Tester avec Postman » — statut `todo`, échéance J+3

---

# Exercice 2 — Composants Blade UI

> **Commit :** `ff6d457` — `feat: add reusable UI components and demo page`

Création de **composants Blade réutilisables** (Alert, Button, Card, Badge) stylés avec **Tailwind CSS** (CDN), deux layouts (`app-layout`, `guest-layout`) et une page de démonstration.

## Commandes (Exo 2)

| Commande | Rôle |
|----------|------|
| `php artisan make:component Alert` | Classe PHP + vue Blade pour `<x-alert>` |
| `php artisan make:component Button` | Classe PHP + vue Blade pour `<x-button>` |
| `php artisan make:component Card` | Classe PHP + vue Blade pour `<x-card>` |
| *(manuel)* `resources/views/components/badge.blade.php` | Composant **anonyme** Blade (sans classe PHP) |
| *(manuel)* `resources/views/components/app-layout.blade.php` | Layout principal avec navbar |
| *(manuel)* `resources/views/components/guest-layout.blade.php` | Layout centré pour pages invitées |
| *(manuel)* `resources/views/components-demo.blade.php` | Page de démonstration |
| `php artisan serve` | Lancer le serveur pour voir `/components-demo` |
| `php artisan route:list --path=components` | Vérifier la route de démo |

## Page de démonstration

Accessible à : **`http://127.0.0.1:8000/components-demo`**

Affiche l'ensemble des composants dans un layout `app-layout` :

```blade
<x-app-layout title="Démo composants">
    <x-alert type="success">...</x-alert>
    <x-alert type="error">...</x-alert>
    <x-card title="Ma première card">
        <x-badge color="green">Actif</x-badge>
        <x-button variant="primary">Valider</x-button>
        <x-button variant="danger">Supprimer</x-button>
    </x-card>
</x-app-layout>
```

## Fichiers du commit `ff6d457`

### `app/View/Components/Alert.php`

Classe PHP du composant `<x-alert>`. Accepte une prop publique `$type` (défaut : `success`). Valeurs possibles : `success`, `error`, `warning`. Délègue le rendu à `resources/views/components/alert.blade.php`.

---

### `app/View/Components/Button.php`

Classe PHP du composant `<x-button>`. Props :

- `$variant` (défaut : `primary`) — `primary`, `danger`, `secondary`
- `$href` (optionnel) — si renseigné, rend un `<a>`, sinon un `<button>`

Délègue le rendu à `resources/views/components/button.blade.php`.

---

### `app/View/Components/Card.php`

Classe PHP du composant `<x-card>`. Accepte une prop `$title` optionnelle affichée en en-tête. Le contenu est injecté via `$slot`. Délègue le rendu à `resources/views/components/card.blade.php`.

---

### `resources/views/components/alert.blade.php`

Vue Blade de l'alerte. Mappe `$type` vers des classes Tailwind (vert, rouge, jaune). Fusionne les classes via `$attributes->merge()`. Affiche le contenu passé en slot.

---

### `resources/views/components/button.blade.php`

Vue Blade du bouton. Mappe `$variant` vers des classes Tailwind. Rend conditionnellement un lien (`<a>`) ou un bouton (`<button>`) selon la présence de `$href`.

---

### `resources/views/components/card.blade.php`

Vue Blade de la carte. Conteneur blanc avec ombre et padding. Affiche un `<h3>` si `$title` est défini, puis le `$slot`.

---

### `resources/views/components/badge.blade.php`

Composant Blade **anonyme** (pas de classe PHP). Déclare `@props(['color' => 'green'])`. Mappe `$color` vers des classes Tailwind (`green`, `red`, `blue`, `yellow`). Rendu en `<span>` arrondi.

---

### `resources/views/components/app-layout.blade.php`

Layout principal de l'application :

- HTML5 avec Tailwind CDN
- Navbar avec nom de l'app et bouton déconnexion (`@auth`)
- Zone `<main>` centrée (`max-w-5xl`) recevant le `$slot`
- Prop `$title` pour le `<title>` de la page

---

### `resources/views/components/guest-layout.blade.php`

Layout pour pages invitées (login, register…) :

- HTML5 avec Tailwind CDN
- Contenu centré verticalement dans une carte blanche (`max-w-md`)
- Prop `$title` pour le `<title>` de la page

---

### `resources/views/components-demo.blade.php`

Page de démonstration assemblant tous les composants. Utilise `<x-app-layout>` comme wrapper et montre les variantes d'alertes, badges, boutons et cards.

---

### `routes/web.php`

Ajout de la route GET `/components-demo` qui retourne la vue `components-demo`. La route `/` existante (page welcome) est conservée.

---

# Exemples de requêtes API

```bash
# Lister toutes les tâches
curl http://127.0.0.1:8000/api/tasks

# Filtrer par statut
curl "http://127.0.0.1:8000/api/tasks?status=todo"

# Créer une tâche
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Ma nouvelle tâche","status":"todo","due_date":"2026-06-15"}'

# Afficher une tâche
curl http://127.0.0.1:8000/api/tasks/1

# Mettre à jour une tâche
curl -X PUT http://127.0.0.1:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"status":"done"}'

# Supprimer une tâche
curl -X DELETE http://127.0.0.1:8000/api/tasks/1
```

---

# Stack technique

- **Laravel** 13.8
- **PHP** 8.3
- **Base de données** SQLite (`database/database.sqlite`)
- **Validation** Form Requests Laravel (Exo 1)
- **ORM** Eloquent (Exo 1)
- **Composants Blade** + **Tailwind CSS** CDN (Exo 2)

---

## Licence

MIT — voir le framework [Laravel](https://laravel.com).
