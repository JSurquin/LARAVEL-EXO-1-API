# API Tasks — Laravel 13

Application Laravel exposant une **API REST CRUD** pour gérer des tâches (`tasks`).  
Base de données **SQLite**, réponses JSON automatiques sur les routes `/api/*`.

---

## Sommaire

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Commandes utilisées](#commandes-utilisées)
- [Endpoints API](#endpoints-api)
- [Structure du projet](#structure-du-projet)
- [Détail des fichiers](#détail-des-fichiers)
- [Exemples de requêtes](#exemples-de-requêtes)

---

## Prérequis

- PHP **8.3+**
- Composer
- Extension PHP `sqlite3`

---

## Installation

```bash
# Cloner le dépôt puis installer les dépendances
composer install

# Copier la configuration d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer le fichier SQLite (s'il n'existe pas)
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate

# (Optionnel) Peupler la base avec des tâches de démonstration
php artisan db:seed --class=TaskSeeder

# Lancer le serveur de développement
php artisan serve
```

L'API est accessible à l'adresse : `http://127.0.0.1:8000/api/tasks`

---

## Commandes utilisées

Commandes Artisan et Composer exécutées lors de la mise en place du projet :

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

---

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

---

## Structure du projet

```
laravel-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php      # Logique CRUD API
│   │   └── Requests/
│   │       ├── StoreTaskRequest.php    # Règles de validation (POST)
│   │       └── UpdateTaskRequest.php   # Règles de validation (PUT/PATCH)
│   └── Models/
│       └── Task.php                    # Modèle Eloquent
├── bootstrap/
│   └── app.php                         # Config routing API + JSON errors
├── database/
│   ├── migrations/
│   │   └── ..._create_tasks_table.php  # Schéma table tasks
│   └── seeders/
│       └── TaskSeeder.php              # Données de démonstration
└── routes/
    └── api.php                         # Route apiResource tasks
```

---

## Détail des fichiers

### `routes/api.php`

Enregistre une **route resource API** pour le contrôleur `TaskController`.  
Laravel génère automatiquement les 5 routes REST (`index`, `store`, `show`, `update`, `destroy`) avec le préfixe `/api`.

---

### `bootstrap/app.php`

Configuration de l'application Laravel 13 :

- **Routing** : charge `routes/api.php` en plus des routes web et console.
- **Exceptions** : force le rendu JSON des erreurs pour toutes les requêtes dont l'URL commence par `api/*` (422 validation, 404, etc.).

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

- `$fillable` — champs assignables en masse (`create` / `update`)
- `$casts` — cast de `due_date` en objet `Carbon` (date)

---

### `app/Http/Requests/StoreTaskRequest.php`

Form Request pour la **création** (`POST /api/tasks`) :

- `title` : requis, string, max 255
- `description` : optionnel
- `status` : optionnel, doit être dans `todo | in_progress | done`
- `due_date` : optionnel, date postérieure à aujourd'hui

---

### `app/Http/Requests/UpdateTaskRequest.php`

Form Request pour la **mise à jour** (`PUT/PATCH /api/tasks/{id}`) :

- Mêmes règles que `StoreTaskRequest`, avec `sometimes|required` sur `title` (obligatoire seulement s'il est présent dans le body)

---

### `app/Http/Controllers/TaskController.php`

Contrôleur API resource :

| Méthode | Comportement |
|---------|--------------|
| `index()` | Liste paginée (10 par page), filtrable par `?status=` |
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

## Exemples de requêtes

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

## Stack technique

- **Laravel** 13.8
- **PHP** 8.3
- **Base de données** SQLite (`database/database.sqlite`)
- **Validation** Form Requests Laravel
- **ORM** Eloquent

---

## Licence

MIT — voir le framework [Laravel](https://laravel.com).
