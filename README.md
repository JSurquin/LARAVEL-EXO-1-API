# Laravel App — Corrections

Projet Laravel 13 regroupant **quatre exercices** distincts, chacun identifié par son commit Git.

| Exercice | Commit | Message |
|----------|--------|---------|
| **Exercice 1** — API REST Tasks | [`c14b900`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/c14b900) | `feat: enhance README and implement task management API with CRUD operations` |
| **Exercice 2** — Composants Blade UI | [`ff6d457`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/ff6d457) | `feat: add reusable UI components and demo page` |
| **Exercice 3** — Auth & Autorisation | [`cbcd333`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/cbcd333) | `feat: integrate Laravel Fortify and Sanctum for user authentication and authorization` |
| **Exercice 4** — Cache & Sessions Redis | [`ad8e8c3`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/ad8e8c3) | `feat: add user preferences and statistics features` |

> Correctif migration doublon Sanctum : [`73a4cec`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/73a4cec) — `fix: remove duplicate migration for sanctum`

> Commit initial du projet : `6c12af0` — `feat: first commit with implementation of basic api task`

---

## Sommaire

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Exercice 1 — API REST Tasks](#exercice-1--api-rest-tasks)
- [Exercice 2 — Composants Blade UI](#exercice-2--composants-blade-ui)
- [Exercice 3 — Auth & Autorisation](#exercice-3--auth--autorisation)
- [Exercice 4 — Cache & Sessions Redis](#exercice-4--cache--sessions-redis)
- [Exemples de requêtes API](#exemples-de-requêtes-api)
- [Stack technique](#stack-technique)

---

## Prérequis

- PHP **8.3+**
- Composer
- Extension PHP `sqlite3`
- **Redis** (Exo 4) — `brew install redis` puis `brew services start redis`

---

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite

# Exo 4 — vérifier que Redis tourne, puis configurer .env :
# SESSION_DRIVER=redis
# CACHE_STORE=redis
# REDIS_CLIENT=predis
# QUEUE_CONNECTION=redis

php artisan migrate
php artisan db:seed --class=TaskSeeder    # Exo 1 — optionnel
php artisan db:seed --class=AdminSeeder   # Exo 3 — compte admin
php artisan serve
```

| Ressource | URL |
|-----------|-----|
| API Tasks | `http://127.0.0.1:8000/api/tasks` *(protégé depuis Exo 3)* |
| Démo composants (Exo 2) | `http://127.0.0.1:8000/components-demo` |
| Login (Exo 3) | `http://127.0.0.1:8000/login` |
| Register (Exo 3) | `http://127.0.0.1:8000/register` |
| Dashboard (Exo 3) | `http://127.0.0.1:8000/dashboard` |
| Articles (Exo 3) | `http://127.0.0.1:8000/posts` |
| Statistiques (Exo 4) | `http://127.0.0.1:8000/stats` |
| Préférences (Exo 4) | `http://127.0.0.1:8000/preferences` |

**Compte admin de test** (via `AdminSeeder`) : `admin@example.com` / `password`

---

# Exercice 1 — API REST Tasks

> **Commit :** `c14b900`

Application d'une **API REST CRUD** pour gérer des tâches (`tasks`).  
Base de données **SQLite**, réponses JSON automatiques sur les routes `/api/*`.

## Commandes (Exo 1)

| Commande | Rôle |
|----------|------|
| `composer create-project laravel/laravel .` | Initialisation du projet Laravel |
| `php artisan make:model Task -m` | Modèle Eloquent + migration `tasks` |
| `php artisan make:controller TaskController --api --model=Task` | Contrôleur API resource |
| `php artisan make:request StoreTaskRequest` | Validation à la création |
| `php artisan make:request UpdateTaskRequest` | Validation à la mise à jour |
| `php artisan migrate` | Application des migrations |
| `php artisan make:seeder TaskSeeder` | Seeder de données de test |
| `php artisan db:seed --class=TaskSeeder` | Insertion des tâches de démo |
| `php artisan route:list --path=api` | Liste des routes API |

## Endpoints API

| Méthode | URL | Action | Code HTTP |
|---------|-----|--------|-----------|
| `GET` | `/api/tasks` | Liste paginée (filtre `?status=`) | 200 |
| `POST` | `/api/tasks` | Créer une tâche | 201 |
| `GET` | `/api/tasks/{id}` | Afficher une tâche | 200 |
| `PUT` / `PATCH` | `/api/tasks/{id}` | Mettre à jour | 200 |
| `DELETE` | `/api/tasks/{id}` | Supprimer | 204 |

> Depuis l'**Exo 3**, toutes les routes `/api/tasks` et `/api/auth/logout` nécessitent un **token Bearer Sanctum**.

## Fichiers du commit `c14b900`

| Fichier | Rôle |
|---------|------|
| `routes/api.php` | Route `apiResource` tasks |
| `bootstrap/app.php` | Routing API + erreurs JSON |
| `database/migrations/..._create_tasks_table.php` | Table `tasks` |
| `app/Models/Task.php` | Modèle Eloquent |
| `app/Http/Requests/StoreTaskRequest.php` | Validation POST |
| `app/Http/Requests/UpdateTaskRequest.php` | Validation PUT/PATCH |
| `app/Http/Controllers/TaskController.php` | CRUD API |
| `database/seeders/TaskSeeder.php` | 3 tâches de démo |

---

# Exercice 2 — Composants Blade UI

> **Commit :** `ff6d457`

Création de **composants Blade réutilisables** (Alert, Button, Card, Badge) stylés avec **Tailwind CSS** (CDN), deux layouts et une page de démonstration.

## Commandes (Exo 2)

| Commande | Rôle |
|----------|------|
| `php artisan make:component Alert` | Composant `<x-alert>` |
| `php artisan make:component Button` | Composant `<x-button>` |
| `php artisan make:component Card` | Composant `<x-card>` |
| *(manuel)* `resources/views/components/badge.blade.php` | Composant anonyme `<x-badge>` |
| *(manuel)* `resources/views/components/app-layout.blade.php` | Layout principal |
| *(manuel)* `resources/views/components/guest-layout.blade.php` | Layout invité |
| *(manuel)* `resources/views/components-demo.blade.php` | Page de démo |

## Fichiers du commit `ff6d457`

| Fichier | Rôle |
|---------|------|
| `app/View/Components/Alert.php` | Classe PHP alerte (`$type`) |
| `app/View/Components/Button.php` | Classe PHP bouton (`$variant`, `$href`) |
| `app/View/Components/Card.php` | Classe PHP carte (`$title`) |
| `resources/views/components/alert.blade.php` | Vue alerte Tailwind |
| `resources/views/components/button.blade.php` | Vue bouton (link ou button) |
| `resources/views/components/card.blade.php` | Vue carte |
| `resources/views/components/badge.blade.php` | Vue badge anonyme |
| `resources/views/components/app-layout.blade.php` | Layout avec navbar *(mis à jour en Exo 3)* |
| `resources/views/components/guest-layout.blade.php` | Layout centré |
| `resources/views/components-demo.blade.php` | Page `/components-demo` |
| `routes/web.php` | Route `/components-demo` |

---

# Exercice 3 — Auth & Autorisation

> **Commit :** `cbcd333` — `feat: integrate Laravel Fortify and Sanctum for user authentication and authorization`

Double système d'authentification :

- **Fortify** (web) — login, register, session cookie, dashboard, CRUD articles
- **Sanctum** (API) — login/logout par token Bearer, protection des routes `/api/tasks`

Autorisation via **rôles** (`user` / `admin`) et **PostPolicy** (auteur ou admin peut modifier/supprimer).

## Commandes (Exo 3)

| Commande | Rôle |
|----------|------|
| `composer require laravel/fortify` | Installe Fortify (auth web) |
| `php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"` | Publie config + migrations Fortify |
| `composer require laravel/sanctum` | Installe Sanctum (auth API tokens) |
| `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"` | Publie config + migration tokens |
| `php artisan migrate` | Applique toutes les migrations (2FA, passkeys, tokens, posts, role) |
| `php artisan make:controller Api/AuthController` | Contrôleur login/logout API |
| `php artisan make:model Post -m` | Modèle Post + migration |
| `php artisan make:controller PostController --resource` | CRUD web des articles |
| `php artisan make:policy PostPolicy --model=Post` | Policy d'autorisation |
| `php artisan make:seeder AdminSeeder` | Seeder compte admin |
| `php artisan db:seed --class=AdminSeeder` | Crée `admin@example.com` |
| `php artisan route:list --path=api` | Vérifie routes auth + tasks |
| `php artisan route:list --path=posts` | Vérifie routes CRUD articles |

## Routes ajoutées

### API (Sanctum)

| Méthode | URL | Auth | Action |
|---------|-----|------|--------|
| `POST` | `/api/auth/login` | Non | Retourne un token Bearer |
| `POST` | `/api/auth/logout` | Token | Révoque le token courant |
| `*` | `/api/tasks` | Token | CRUD tasks (Exo 1, maintenant protégé) |

### Web (Fortify + auth middleware)

| URL | Action |
|-----|--------|
| `/login` | Formulaire de connexion |
| `/register` | Formulaire d'inscription |
| `/dashboard` | Tableau de bord (auth requis) |
| `/posts` | CRUD articles (auth requis) |

## Fichiers du commit `cbcd333`

### Authentification API

#### `app/Http/Controllers/Api/AuthController.php`

Contrôleur Sanctum :

- `login()` — valide email/password, tente `Auth::attempt()`, retourne `{ token }` (201)
- `logout()` — supprime le token Bearer courant via `currentAccessToken()->delete()`

---

#### `routes/api.php`

- Route publique `POST /auth/login`
- Groupe `auth:sanctum` protégeant `tasks` (resource) et `POST /auth/logout`

---

### Authentification Web (Fortify)

#### `app/Providers/FortifyServiceProvider.php`

Configure Fortify :

- Vues custom : `auth.login`, `auth.register`
- Actions : `CreateNewUser`, `UpdateUserProfileInformation`, `UpdateUserPassword`, `ResetUserPassword`
- Rate limiting login (5/min)

---

#### `bootstrap/providers.php`

Enregistre `FortifyServiceProvider` dans le bootstrap Laravel 13.

---

#### `app/Actions/Fortify/CreateNewUser.php`

Logique d'inscription : valide name/email/password, force `role = 'user'` à la création.

---

#### `app/Actions/Fortify/PasswordValidationRules.php`

Trait partagé : règles `Password::default()` + `confirmed`.

---

#### `app/Actions/Fortify/ResetUserPassword.php`

Réinitialisation mot de passe oublié (Fortify).

---

#### `app/Actions/Fortify/UpdateUserPassword.php`

Changement de mot de passe connecté (vérifie `current_password`).

---

#### `app/Actions/Fortify/UpdateUserProfileInformation.php`

Mise à jour nom/email du profil.

---

#### `config/fortify.php`

Configuration publiée Fortify (guard `web`, features 2FA/passkeys, rate limiting).

---

#### `config/sanctum.php`

Configuration publiée Sanctum (domains stateful, guard, expiration tokens).

---

#### `composer.json` / `composer.lock`

Ajout des dépendances `laravel/fortify` ^1.37 et `laravel/sanctum` ^4.3.

---

### Modèles & Autorisation

#### `app/Models/User.php`

- Trait `HasApiTokens` (Sanctum)
- Attribut PHP `#[Fillable]` incluant `role`
- Relation `posts()` hasMany
- Méthode `isAdmin()` : `$this->role === 'admin'`

---

#### `app/Models/Post.php`

Modèle article : `$fillable` (`title`, `body`, `user_id`), relation `user()` belongsTo.

---

#### `app/Policies/PostPolicy.php`

Règles d'autorisation :

| Action | Règle |
|--------|-------|
| `viewAny`, `view`, `create` | Tout utilisateur authentifié |
| `update`, `delete` | Auteur (`user_id`) **OU** admin |

Utilisée via `$this->authorize()` dans le contrôleur et `@can` dans les vues.

---

#### `app/Http/Controllers/PostController.php`

CRUD web resource des articles, protégé par `PostPolicy` à chaque action.

---

### Migrations

| Fichier | Rôle |
|---------|------|
| `..._add_two_factor_columns_to_users_table.php` | Colonnes 2FA Fortify |
| `..._create_passkeys_table.php` | Table passkeys WebAuthn |
| `..._create_personal_access_tokens_table.php` (123705) | Table tokens Sanctum |
| `..._create_personal_access_tokens_table.php` (124242) | Doublon Sanctum *(second publish)* |
| `..._create_posts_table.php` | Table `posts` (title, body, user_id FK) |
| `..._add_role_to_users_table.php` | Colonne `role` enum (`user`, `admin`) |

---

### Seeders

#### `database/seeders/AdminSeeder.php`

Crée un admin : `admin@example.com` / `password` / `role = admin`.

---

### Vues

| Fichier | Rôle |
|---------|------|
| `resources/views/auth/login.blade.php` | Formulaire login Fortify |
| `resources/views/auth/register.blade.php` | Formulaire inscription Fortify |
| `resources/views/dashboard.blade.php` | Accueil connecté avec rôle affiché |
| `resources/views/posts/index.blade.php` | Liste articles + `@can` edit/delete |
| `resources/views/posts/create.blade.php` | Formulaire création |
| `resources/views/posts/edit.blade.php` | Formulaire édition |
| `resources/views/posts/show.blade.php` | Détail article |
| `resources/views/components/app-layout.blade.php` | Navbar auth (login/register ou dashboard/posts/logout) |

---

### Routes Web

#### `routes/web.php`

- `GET /dashboard` — middleware `auth`
- `Route::resource('posts', PostController::class)` — middleware `auth`

---

# Exercice 4 — Cache & Sessions Redis

> **Commit :** `ad8e8c3` — `feat: add user preferences and statistics features`

Mise en place de **Redis** comme driver pour le **cache applicatif** et les **sessions utilisateur**, avec deux fonctionnalités :

1. **Préférences** — thème (`light` / `dark`) et langue (`fr` / `en`) stockés en **session Redis**
2. **Statistiques** — compteurs users/tasks mis en cache via `Cache::remember()` (TTL 1 h), vidables par l'admin

### Configuration `.env` (Exo 4)

| Variable | Valeur | Rôle |
|----------|--------|------|
| `SESSION_DRIVER` | `redis` | Sessions web (préférences, flash messages) dans Redis |
| `CACHE_STORE` | `redis` | Cache Laravel (clé `stats`) dans Redis DB 1 |
| `REDIS_CLIENT` | `predis` | Client PHP Redis (package `predis/predis`) |
| `QUEUE_CONNECTION` | `redis` | Files d'attente Redis *(optionnel)* |

### Commandes (Exo 4)

| Commande | Rôle |
|----------|------|
| `brew install redis && brew services start redis` | Installer et démarrer Redis (macOS) |
| `composer require predis/predis` | Client Redis PHP (ajouté au `composer.json`) |
| `php artisan make:controller PreferenceController` | Contrôleur préférences session |
| `php artisan make:controller StatsController` | Contrôleur stats + vidage cache |
| `redis-cli ping` | Vérifier que Redis répond `PONG` |
| `redis-cli FLUSHDB` | Vider la base Redis courante (debug) |
| `redis-cli KEYS "*stats*"` | Inspecter les clés cache Laravel |
| `tail -f storage/logs/laravel.log` | Observer les requêtes SQL (`DB::listen`) |

### Routes ajoutées

| Méthode | URL | Auth | Action |
|---------|-----|------|--------|
| `GET` | `/preferences` | Non* | Formulaire thème / langue |
| `POST` | `/preferences` | Non* | Enregistre en session Redis |
| `GET` | `/stats` | Non* | Affiche stats (cache Redis 1 h) |
| `POST` | `/cache/flush` | Oui (admin) | `Cache::forget('stats')` |

\* Routes publiques dans le code actuel ; à protéger par `auth` en production.

### Fonctionnement cache / sessions

```
1ère visite /stats  →  Cache MISS  →  2 requêtes SQL (COUNT users, COUNT tasks)
                    →  résultat stocké dans Redis (clé laravel-cache-stats, TTL 3600s)

2ème visite /stats  →  Cache HIT   →  0 requête SQL (vérifiable dans laravel.log)

POST /cache/flush (admin)  →  Cache::forget('stats')  →  prochaine visite = MISS
```

Équivalent CLI du vidage global : `redis-cli FLUSHDB` (vide toute la DB Redis sélectionnée).

### Fichiers du commit `ad8e8c3`

#### `app/Http/Controllers/StatsController.php`

- `index()` — `Cache::remember('stats', 3600, fn)` retourne `{ users, tasks }`
- `flush()` — `Cache::forget('stats')`, réservé admin (`isAdmin()`), redirect dashboard + flash

---

#### `app/Http/Controllers/PreferenceController.php`

- `index()` — lit `session('theme')` et `session('locale')` pour pré-remplir le formulaire
- `store()` — valide et persiste `theme` / `locale` via `session([...])`

---

#### `app/Providers/AppServiceProvider.php`

- `DB::listen()` — log chaque requête SQL pour démontrer cache hit (0 SQL) vs miss (2 SQL)

---

#### `routes/web.php`

- Routes `/preferences`, `/stats`, `/cache/flush`
- Imports `PreferenceController` et `StatsController`

---

#### `resources/views/stats/index.blade.php`

- Affiche `$stats['users']` et `$stats['tasks']`
- Bouton « Vider le cache » visible uniquement pour les admins (`@if(auth()->user()->isAdmin())`)

---

#### `resources/views/preferences/index.blade.php`

- Formulaire select thème (clair/sombre) et langue (fr/en)
- Affiche les valeurs session actuelles

---

#### `resources/views/dashboard.blade.php`

- Liens vers `/stats` et `/preferences`
- Affichage du message flash `session('success')` après vidage cache

---

#### `resources/views/components/app-layout.blade.php`

- Classe CSS conditionnelle selon `session('theme')` (fond sombre si `dark`)
- Liens navbar : Statistiques, Préférences

---

#### `composer.json` / `composer.lock`

- Ajout de `predis/predis` ^3.5 (client Redis sans extension phpredis)

---

#### `.env.example`

- `SESSION_DRIVER=redis`, `CACHE_STORE=redis`, `REDIS_CLIENT=predis`, `QUEUE_CONNECTION=redis`

---

### Migration doublon (résolu)

La migration `2026_06_02_124242_create_personal_access_tokens_table` échouait car la table existait déjà (double `vendor:publish` Sanctum).  
Supprimée dans le commit **`73a4cec`**.

---

# Exemples de requêtes API

```bash
# 1. Login API — récupérer un token
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# 2. Lister les tâches (token requis depuis Exo 3)
curl http://127.0.0.1:8000/api/tasks \
  -H "Authorization: Bearer VOTRE_TOKEN"

# 3. Créer une tâche
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Authorization: Bearer VOTRE_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Ma tâche","status":"todo","due_date":"2026-06-15"}'

# 4. Logout API — révoque le token
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer VOTRE_TOKEN"
```

---

# Stack technique

- **Laravel** 13.8
- **PHP** 8.3
- **SQLite** (`database/database.sqlite`)
- **Eloquent** + Form Requests (Exo 1)
- **Composants Blade** + Tailwind CDN (Exo 2)
- **Laravel Fortify** — auth web session (Exo 3)
- **Laravel Sanctum** — auth API tokens Bearer (Exo 3)
- **Policies** — autorisation par rôle et propriétaire (Exo 3)
- **Redis** + **Predis** — cache applicatif et sessions (Exo 4)
- **Cache::remember** / **Cache::forget** — statistiques mises en cache (Exo 4)

---

## Licence

MIT — voir le framework [Laravel](https://laravel.com).
