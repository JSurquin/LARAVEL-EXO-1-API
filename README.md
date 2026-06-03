# Laravel App — Corrections

Projet Laravel 13 regroupant **sept exercices** distincts, chacun identifié par son commit Git.

| Exercice | Commit | Message |
|----------|--------|---------|
| **Exercice 1** — API REST Tasks | [`c14b900`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/c14b900) | `feat: enhance README and implement task management API with CRUD operations` |
| **Exercice 2** — Composants Blade UI | [`ff6d457`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/ff6d457) | `feat: add reusable UI components and demo page` |
| **Exercice 3** — Auth & Autorisation | [`cbcd333`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/cbcd333) | `feat: integrate Laravel Fortify and Sanctum for user authentication and authorization` |
| **Exercice 4** — Cache & Sessions Redis | [`ad8e8c3`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/ad8e8c3) | `feat: add user preferences and statistics features` |
| **Exercice 5** — Newsletter / Queue / Horizon | [`0070dc3`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/0070dc3) | `feat: implement newsletter management system` |
| **Exercice 6** — Tests Pest & Dusk | [`9f4277e`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/9f4277e) | `feat: set up testing environment with Dusk and Pest` |
| **Exercice 7** — Docker | [`6beacf4`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/6beacf4) | `feat: set up Docker environment for Laravel application` |

> Correctif migration doublon Sanctum : [`73a4cec`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/73a4cec) — `fix: remove duplicate migration for sanctum`

> Commit initial du projet : `6c12af0` — `feat: first commit with implementation of basic api task`  
> Documentation Exo 4 (commentaires + README) : [`60084f6`](https://github.com/JSurquin/LARAVEL-EXO-1-API/commit/60084f6)

---

## Vue d'ensemble des corrections

| # | Thème | Ce qui a été fait |
|---|-------|-------------------|
| **1** | API REST | CRUD `tasks` en JSON, validation, SQLite, routes `/api/tasks` |
| **2** | Composants UI | Blade `<x-alert>`, `<x-button>`, `<x-card>`, `<x-badge>`, page `/components-demo` |
| **3** | Auth | Fortify (web), Sanctum (API), rôles, `PostPolicy`, CRUD `/posts` |
| **4** | Cache & sessions | Redis (`CACHE_STORE` + `SESSION_DRIVER`), stats en cache, préférences utilisateur |
| **5** | Newsletter / Queue | Mailable `NewsletterMail`, Job `SendNewsletterJob`, Horizon, Notification admin |
| **6** | Tests Pest & Dusk | Tests Feature (API, Policy, Job), tests Browser (login Fortify), factories, `.env.dusk.local` |
| **7** | Docker | `docker-compose` : PHP-FPM, Nginx, MySQL, Redis, Mailpit, worker `queue:work` |

---

## Sommaire

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Exercice 1 — API REST Tasks](#exercice-1--api-rest-tasks)
- [Exercice 2 — Composants Blade UI](#exercice-2--composants-blade-ui)
- [Exercice 3 — Auth & Autorisation](#exercice-3--auth--autorisation)
- [Exercice 4 — Cache & Sessions Redis](#exercice-4--cache--sessions-redis)
- [Exercice 5 — Newsletter / Queue / Horizon](#exercice-5--newsletter--queue--horizon)
- [Exercice 6 — Tests Pest & Dusk](#exercice-6--tests-pest--dusk)
- [Exercice 7 — Docker](#exercice-7--docker)
- [Exemples de requêtes API](#exemples-de-requêtes-api)
- [Stack technique](#stack-technique)

---

## Prérequis

- PHP **8.3+**
- Composer
- Extension PHP `sqlite3`
- **Redis** (Exo 4 & 5) — `brew install redis` puis `brew services start redis`
- **Laravel Horizon** (Exo 5) — `composer require laravel/horizon`
- **Google Chrome** + **ChromeDriver** (Exo 6) — pour les tests Browser Dusk
- **Pest** + **Laravel Dusk** (Exo 6) — `composer require pestphp/pest pestphp/pest-plugin-laravel laravel/dusk --dev`
- **Docker** + **Docker Compose** (Exo 7) — conteneurisation complète de l'application

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
php artisan db:seed --class=TaskSeeder       # Exo 1 — optionnel
php artisan db:seed --class=AdminSeeder      # Exo 3 — compte admin
php artisan db:seed --class=SubscriberSeeder # Exo 5 — abonnés de démo (facultatif)
php artisan serve

# Exo 5 — lancer le worker de queue (dans un terminal séparé)
php artisan queue:work
# OU démarrer Horizon (dashboard de monitoring des queues)
php artisan horizon

# Exo 6 — tests automatisés
php artisan test              # Lance tous les tests Pest Feature (+ Unit)
php artisan dusk              # Lance les tests Browser (serveur + ChromeDriver requis)
php artisan dusk:chrome-driver --detect  # Installe ChromeDriver correspondant à votre Chrome
```

### Installation Docker (Exo 7)

```bash
cp .env.example .env
# Adapter .env pour Docker (noms de services = hôtes réseau interne) :
# DB_CONNECTION=mysql
# DB_HOST=db
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=laravel
# DB_PASSWORD=secret
# REDIS_HOST=redis
# MAIL_MAILER=smtp
# MAIL_HOST=mailpit
# MAIL_PORT=1025
# QUEUE_CONNECTION=redis

docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=AdminSeeder
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
| Newsletters (Exo 5) | `http://127.0.0.1:8000/newsletters` |
| Horizon — dashboard queues (Exo 5) | `http://127.0.0.1:8000/horizon` |
| Application (Exo 7 — Docker) | `http://localhost:8080` |
| Mailpit — e-mails capturés (Exo 7) | `http://localhost:8025` |

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

> **Commit fonctionnel :** `ad8e8c3` — `feat: add user preferences and statistics features`  
> **Commit doc :** `60084f6` — commentaires FR + compléments README

Passage du cache et des sessions de **database** vers **Redis** (`predis/predis`). Deux fonctionnalités métier :

1. **Sessions Redis** — préférences utilisateur (thème clair/sombre, langue fr/en) via `session()`
2. **Cache Redis** — statistiques (`User::count()`, `Task::count()`) mises en cache 1 h avec `Cache::remember()`

## Objectifs pédagogiques

- Comprendre la différence **session** (données par utilisateur) vs **cache** (données partagées, performance)
- Observer un **cache hit** (0 requête SQL) vs **cache miss** (2 requêtes) grâce à `DB::listen()` dans les logs
- Vider le cache côté app (`Cache::forget`) ou côté Redis (`redis-cli FLUSHDB`)

## Configuration `.env` (Exo 4)

| Variable | Valeur | Rôle |
|----------|--------|------|
| `SESSION_DRIVER` | `redis` | Sessions web (préférences, messages flash) |
| `CACHE_STORE` | `redis` | Cache Laravel — clé `stats`, DB Redis `1` |
| `REDIS_CLIENT` | `predis` | Client PHP (sans extension `phpredis`) |
| `REDIS_HOST` | `127.0.0.1` | Serveur Redis local |
| `REDIS_PORT` | `6379` | Port par défaut |
| `QUEUE_CONNECTION` | `redis` | Files d'attente *(optionnel)* |

Fichier modèle : `.env.example` (valeurs Redis documentées).

## Commandes (Exo 4)

| Commande | Rôle |
|----------|------|
| `brew install redis && brew services start redis` | Installer / démarrer Redis (macOS) |
| `composer require predis/predis` | Client Redis PHP |
| `php artisan make:controller PreferenceController` | Gestion préférences session |
| `php artisan make:controller StatsController` | Stats + vidage cache |
| `redis-cli ping` | Doit répondre `PONG` |
| `redis-cli FLUSHDB` | Vide la base Redis active (debug, comme en terminal) |
| `redis-cli KEYS "*stats*"` | Liste les clés cache Laravel |
| `tail -f storage/logs/laravel.log` | Voir les requêtes SQL loguées par `DB::listen` |

## Routes web (Exo 4)

| Méthode | URL | Auth | Action |
|---------|-----|------|--------|
| `GET` | `/preferences` | — | Affiche formulaire thème / langue |
| `POST` | `/preferences` | — | Enregistre en session Redis |
| `GET` | `/stats` | — | Compteurs users + tasks (depuis cache ou BDD) |
| `POST` | `/cache/flush` | Admin | Supprime la clé `stats` du cache |

## Fonctionnement cache / sessions

```text
1ère visite /stats
  → Cache MISS
  → 2 requêtes SQL : SELECT COUNT(*) users, SELECT COUNT(*) tasks
  → Résultat stocké dans Redis (TTL 3600 s, clé préfixée laravel-cache-stats)

2ème visite /stats
  → Cache HIT
  → 0 requête SQL (vérifier storage/logs/laravel.log)

POST /cache/flush (admin connecté)
  → Cache::forget('stats')
  → Redirect /dashboard + message « Cache vidé ! »
  → Prochaine visite /stats = MISS à nouveau
```

**Test manuel :**

1. Aller sur `/stats` → noter 2 lignes SQL dans `laravel.log`
2. Recharger `/stats` → aucune nouvelle requête SQL
3. Se connecter en admin → cliquer « Vider le cache » ou exécuter `redis-cli FLUSHDB`
4. Recharger `/stats` → 2 requêtes SQL réapparaissent

## Fichiers du commit `ad8e8c3` (+ doc `60084f6`)

| Fichier | Rôle |
|---------|------|
| `app/Http/Controllers/StatsController.php` | `Cache::remember('stats', 3600)` + `Cache::forget` (admin) |
| `app/Http/Controllers/PreferenceController.php` | Lecture/écriture `session('theme')`, `session('locale')` |
| `app/Providers/AppServiceProvider.php` | `DB::listen()` pour tracer les requêtes SQL |
| `routes/web.php` | Routes `/preferences`, `/stats`, `/cache/flush` |
| `resources/views/stats/index.blade.php` | Cartes statistiques + bouton vidage cache admin |
| `resources/views/preferences/index.blade.php` | Formulaire thème et langue |
| `resources/views/dashboard.blade.php` | Liens stats/préférences + flash success |
| `resources/views/components/app-layout.blade.php` | Thème sombre via `session('theme')`, liens navbar |
| `composer.json` | Dépendance `predis/predis` ^3.5 |
| `.env.example` | `SESSION_DRIVER=redis`, `CACHE_STORE=redis` |

### Détail : `StatsController.php`

| Méthode | Comportement |
|---------|--------------|
| `index()` | `Cache::remember('stats', 3600, fn)` — exécute les `COUNT` uniquement si absent du cache |
| `flush()` | `abort_unless(isAdmin())` puis `Cache::forget('stats')` — redirect dashboard |

### Détail : `PreferenceController.php`

| Méthode | Comportement |
|---------|--------------|
| `index()` | Passe `theme` et `locale` depuis la session à la vue |
| `store()` | Valide `light|dark` et `fr|en`, puis `session([...])` |

### Détail : `app-layout.blade.php` (Exo 4)

- `<main class="... {{ session('theme') === 'dark' ? 'bg-gray-900 text-white' : '' }}">` — thème appliqué globalement
- Liens **Statistiques** et **Préférences** dans la navbar (utilisateur connecté)

## Correctif migration (commit `73a4cec`)

`php artisan migrate` échouait sur `2026_06_02_124242_create_personal_access_tokens_table` (table déjà créée par `123705`).  
Migration doublon **supprimée** — une seule table `personal_access_tokens` suffit pour Sanctum.

## Dépendances Exo 3 requises pour Exo 4

- `User::count()` et `Task::count()` (modèles Exo 1 + 3)
- `auth()->user()->isAdmin()` (rôle admin, Exo 3 + `AdminSeeder`)
- Layout `<x-app-layout>` (Exo 2)

---

# Exercice 5 — Newsletter / Queue / Horizon

> **Commit :** `0070dc3` — `feat: implement newsletter management system`

Système d'envoi de **newsletters asynchrone** via la **queue Redis** et **Laravel Horizon** :

1. L'admin remplit un formulaire (sujet + corps) → la newsletter est créée en base
2. Un **Job** (`SendNewsletterJob`) est dispatché en queue Redis
3. Le **worker** (Horizon) consomme le job : envoie un e-mail à chaque abonné via `NewsletterMail`
4. Une **Notification** (`NewsletterSentNotification`) est envoyée à l'admin à la fin

## Objectifs pédagogiques

- Comprendre la différence **synchrone** (traitement immédiat) vs **asynchrone** (queue)
- Utiliser un **Mailable** pour encapsuler un e-mail réutilisable
- Utiliser un **Job** avec `ShouldQueue` pour l'envoi en arrière-plan
- Utiliser le système de **Notifications** Laravel pour alerter un utilisateur
- Monitorer les jobs avec **Laravel Horizon** (tableau de bord Redis)

## Configuration `.env` (Exo 5)

| Variable | Valeur | Rôle |
|----------|--------|------|
| `QUEUE_CONNECTION` | `redis` | Jobs envoyés dans la queue Redis |
| `MAIL_MAILER` | `log` *(dev)* ou `smtp` | Backend d'envoi d'e-mails |
| `REDIS_CLIENT` | `predis` | Client PHP Redis (partagé avec Exo 4) |

## Commandes (Exo 5)

| Commande | Rôle |
|----------|------|
| `php artisan make:model Newsletter -m` | Modèle + migration table `newsletters` |
| `php artisan make:model Subscriber -m` | Modèle + migration table `subscribers` |
| `php artisan make:mail NewsletterMail` | Classe Mailable pour l'e-mail newsletter |
| `php artisan make:job SendNewsletterJob` | Job asynchrone d'envoi |
| `php artisan make:notification NewsletterSentNotification` | Notification de confirmation admin |
| `php artisan make:controller NewsletterController` | Contrôleur (index / create / store) |
| `composer require laravel/horizon` | Installe Horizon (dashboard queues Redis) |
| `php artisan horizon:install` | Publie les assets et la config Horizon |
| `php artisan migrate` | Crée les tables `newsletters` et `subscribers` |
| `php artisan queue:work` | Lance un worker simple (sans Horizon) |
| `php artisan horizon` | Lance le supervisor Horizon |

## Routes web (Exo 5)

| Méthode | URL | Auth | Action |
|---------|-----|------|--------|
| `GET` | `/newsletters` | Oui | Liste des newsletters + statut d'envoi |
| `GET` | `/newsletters/create` | Oui | Formulaire de création |
| `POST` | `/newsletters` | Oui | Valide, crée en BDD, dispatche le job |
| `GET` | `/horizon` | Admin | Dashboard de monitoring des queues |

## Flux d'envoi

```text
Formulaire POST /newsletters
  → NewsletterController@store
  → Newsletter::create(['subject', 'body'])       — enregistrement en BDD (sent_at = null)
  → SendNewsletterJob::dispatch($newsletter, $admin) — job poussé dans la queue Redis

Worker (php artisan horizon / queue:work)
  → SendNewsletterJob::handle()
    → newsletter->refresh()                        — recharge pour vérifier idempotence
    → Subscriber::all()                            — récupère tous les abonnés
    → foreach → Mail::to()->send(NewsletterMail)   — e-mail individuel par abonné
    → newsletter->update(['sent_at' => now()])     — marque comme envoyée
    → admin->notify(NewsletterSentNotification)    — e-mail de confirmation à l'admin

Vue /newsletters (rechargée)
  → sent_at renseigné → "✓ Envoyée le ..."
```

## Fichiers du commit `0070dc3`

| Fichier | Rôle |
|---------|------|
| `app/Models/Newsletter.php` | Modèle Eloquent — `subject`, `body`, `sent_at` (cast datetime) |
| `app/Models/Subscriber.php` | Modèle Eloquent — `email` (unique), `name` |
| `database/migrations/..._create_newsletters_table.php` | Table `newsletters` |
| `database/migrations/..._create_subscribers_table.php` | Table `subscribers` |
| `app/Mail/NewsletterMail.php` | Mailable — enveloppe (sujet) + vue `emails.newsletter` |
| `app/Jobs/SendNewsletterJob.php` | Job queue — envoi à tous les abonnés + notification admin |
| `app/Notifications/NewsletterSentNotification.php` | Notification mail envoyée à l'admin |
| `app/Http/Controllers/NewsletterController.php` | Contrôleur — index / create / store |
| `resources/views/newsletters/index.blade.php` | Liste newsletters avec statut envoi |
| `resources/views/newsletters/create.blade.php` | Formulaire sujet + corps |
| `resources/views/emails/newsletter.blade.php` | Template HTML de l'e-mail abonné |
| `routes/web.php` | Route resource `newsletters` (index / create / store) protégée par auth |

### Détail : `SendNewsletterJob.php`

| Propriété / Méthode | Comportement |
|---------------------|--------------|
| `$tries = 3` | Relance automatique du job jusqu'à 3 fois en cas d'erreur |
| `newsletter->refresh()` | Recharge le modèle depuis la BDD (idempotence — évite les doublons) |
| Guard `if ($newsletter->sent_at)` | Court-circuite si déjà envoyée (rejeu de job, double dispatch) |
| `Subscriber::all()` | Charge tous les abonnés en mémoire pour l'itération |
| `Mail::to()->send(NewsletterMail)` | Envoi synchrone dans le contexte du worker |
| `newsletter->update(['sent_at'])` | Marque l'envoi comme terminé avec horodatage |
| `admin->notify(...)` | Déclenche `NewsletterSentNotification` vers l'admin |

### Détail : `NewsletterMail.php`

| Méthode | Comportement |
|---------|--------------|
| `envelope()` | Sujet de l'e-mail = `$newsletter->subject` |
| `content()` | Vue `emails.newsletter` + variable `$newsletter` passée à Blade |
| `attachments()` | Aucune pièce jointe (tableau vide) |

### Détail : `NewsletterSentNotification.php`

| Canal | Comportement |
|-------|--------------|
| `via()` | `['mail']` — envoyée uniquement par e-mail |
| `toMail()` | Sujet fixe + ligne résumant le titre et le nombre d'abonnés touchés |

---

# Exercice 6 — Tests Pest & Dusk

> **Commit :** `9f4277e` — `feat: set up testing environment with Dusk and Pest`

Mise en place d'une **suite de tests automatisés** couvrant les exercices précédents :

1. **Pest (Feature)** — tests HTTP/API, policies et job newsletter (SQLite en mémoire, `RefreshDatabase`)
2. **Laravel Dusk (Browser)** — tests end-to-end dans Chrome (login Fortify, page d'accueil)
3. **Factories** — génération de données factices (`User`, `Task`, `Post`, `Subscriber`)

## Objectifs pédagogiques

- Écrire des tests **Feature** avec la syntaxe Pest (`it()`, `expect()`, `uses()`)
- Tester une **API REST** sans token (route dédiée aux tests) via `getJson` / `postJson`
- Tester une **Policy** avec `$user->can('update', $post)` et `expect()->toBeTrue()`
- Tester un **Job** avec `Mail::fake()` et `Notification::fake()`
- Automatiser un **parcours navigateur** avec Dusk (`visit`, `type`, `press`, `assertPathIs`)
- Comprendre la différence **test unitaire/feature** (rapide, sans navigateur) vs **test browser** (lent, Chrome réel)

## Configuration

| Fichier | Rôle |
|---------|------|
| `phpunit.xml` | BDD SQLite `:memory:`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array` pour les tests Feature |
| `.env.dusk.local` | Environnement chargé par `php artisan dusk` (APP_URL, Redis, mail SMTP local) |
| `tests/Pest.php` | Lie `Feature` → `TestCase` + `RefreshDatabase`, `Browser` → `DuskTestCase` |

## Commandes (Exo 6)

| Commande | Rôle |
|----------|------|
| `composer require pestphp/pest pestphp/pest-plugin-laravel --dev` | Installe Pest + plugin Laravel |
| `composer require laravel/dusk --dev` | Installe Laravel Dusk |
| `php artisan dusk:install` | Publie `DuskTestCase`, dossiers Browser, `.env.dusk.local` |
| `php artisan dusk:chrome-driver --detect` | Installe ChromeDriver (version = Chrome installé) |
| `php artisan make:factory PostFactory` | Factory articles (PostPolicyTest) |
| `php artisan make:factory TaskFactory` | Factory tâches (TaskApiTest) |
| `php artisan test` | Exécute les tests Pest Feature + Unit |
| `php artisan test --filter=TaskApi` | Lance uniquement les tests dont le nom contient « TaskApi » |
| `php artisan dusk` | Exécute les tests Browser (nécessite `php artisan serve` + ChromeDriver) |

## Prérequis pour `php artisan dusk`

1. Terminal 1 : `php artisan serve` (app sur `http://localhost:8000`)
2. Terminal 2 : `php artisan dusk:chrome-driver` (ou `--detect` une fois)
3. Terminal 3 : `php artisan dusk`

Les captures d'écran et logs console sont stockés dans `tests/Browser/screenshots/` et `tests/Browser/console/`.

## Tests Feature (Pest)

| Fichier | Ce qui est testé | Exercice source |
|---------|------------------|-----------------|
| `tests/Feature/TaskApiTest.php` | CRUD `/api/tasks`, validation, filtre `?status=`, 404 | Exo 1 |
| `tests/Feature/PostPolicyTest.php` | `PostPolicy` — auteur, tiers, admin (update/delete) | Exo 3 |
| `tests/Feature/NewsletterJobTest.php` | `SendNewsletterJob` — mails, `sent_at`, notification admin | Exo 5 |

### Route API dédiée aux tests

Depuis l'**Exo 3**, `/api/tasks` est protégé par `auth:sanctum`. Pour tester l'API sans token, une **deuxième route** publique a été ajoutée dans `routes/api.php` (commentée « Exo 6 »). En production, seule la route du groupe `auth:sanctum` doit rester active.

## Tests Browser (Dusk + Pest)

| Fichier | Scénario |
|---------|----------|
| `tests/Browser/ExampleTest.php` | Visite `/` → assertSee `Laravel` |
| `tests/Browser/LoginTest.php` | Login Fortify → redirection `/dashboard` |
| `tests/DuskTestCase.php` | Démarre ChromeDriver, configure Chrome (headless) |
| `tests/Browser/Pages/Page.php` | Page Object abstrait (raccourcis `@element`) |
| `tests/Browser/Pages/HomePage.php` | Page Object page d'accueil |

## Factories

| Factory | Modèle | Usage principal |
|---------|--------|-----------------|
| `UserFactory` | `User` | Dusk login, PostPolicy, NewsletterJob (`role`, `password` en clair) |
| `TaskFactory` | `Task` | TaskApiTest — statuts aléatoires, dates optionnelles |
| `PostFactory` | `Post` | PostPolicyTest — `Post::factory()->for($user)` |
| `SubscriberFactory` | `Subscriber` | NewsletterJobTest — `count(3)` abonnés |

## Fichiers du commit `9f4277e`

| Fichier | Rôle |
|---------|------|
| `tests/Pest.php` | Configuration globale Pest (Feature + Browser) |
| `tests/DuskTestCase.php` | ChromeDriver + RemoteWebDriver (headless) |
| `tests/Feature/TaskApiTest.php` | 8 tests API tasks |
| `tests/Feature/PostPolicyTest.php` | 5 tests PostPolicy |
| `tests/Feature/NewsletterJobTest.php` | 3 tests SendNewsletterJob (fakes Mail/Notification) |
| `tests/Browser/ExampleTest.php` | Test Dusk page d'accueil |
| `tests/Browser/LoginTest.php` | Test Dusk login Fortify |
| `tests/Browser/Pages/Page.php` | Page Object de base |
| `tests/Browser/Pages/HomePage.php` | Page Object accueil |
| `database/factories/TaskFactory.php` | Données factices Task |
| `database/factories/PostFactory.php` | Données factices Post |
| `database/factories/UserFactory.php` | Données factices User (+ `role`) |
| `database/factories/SubscriberFactory.php` | Données factices Subscriber *(utilisée par NewsletterJobTest)* |
| `app/Models/Post.php` | Ajout trait `HasFactory` |
| `routes/api.php` | Route `tasks` publique pour tests Feature |
| `.env.dusk.local` | Variables d'environnement Dusk |
| `composer.json` | `pestphp/pest`, `pestphp/pest-plugin-laravel`, `laravel/dusk` |

### Détail : `TaskApiTest.php`

| Test | Assertion clé |
|------|---------------|
| `lists tasks` | `assertJsonCount(5, 'data')` — pagination Laravel |
| `creates a task` | `assertCreated()` + `assertDatabaseHas` |
| `validates title on store` | `assertUnprocessable()` — corps vide |
| `filters by status` | `?status=todo` → 3 résultats sur 5 tâches |

### Détail : `NewsletterJobTest.php`

| Test | Technique |
|------|-----------|
| `sends mail to subscribers` | `Mail::fake()` + `Mail::assertSent(NewsletterMail::class, 3)` |
| `updates sent_at after job` | `expect($newsletter->fresh()->sent_at)->not->toBeNull()` |
| `notifies admin after send` | `Notification::fake()` + `assertSentTo($admin, ...)` |

### Détail : `LoginTest.php` (Dusk)

```text
User::factory()->create(['password' => 'password'])
  → browse → visit('/login')
  → type email + password
  → press('Se connecter')
  → assertPathIs('/dashboard')
```

## Dépendances des exercices précédents

- **Exo 1** — modèle `Task`, routes `/api/tasks`, `TaskController`
- **Exo 3** — `Post`, `PostPolicy`, `User` avec `role`, Fortify login `/login`
- **Exo 5** — `SendNewsletterJob`, `NewsletterMail`, `NewsletterSentNotification`, `Subscriber`

---

# Exercice 7 — Docker

> **Commit :** `6beacf4` — `feat: set up Docker environment for Laravel application`

Conteneurisation complète de l'application Laravel avec **Docker Compose** : six services orchestrés sur un réseau interne `laravel`, remplaçant l'installation locale (PHP, SQLite, Redis brew) par un environnement reproductible.

## Objectifs pédagogiques

- Comprendre l'architecture **Nginx + PHP-FPM** (reverse proxy → FastCGI)
- Séparer les responsabilités en **services** (app, base, cache, mail, worker)
- Utiliser les **noms de service** comme hôtes (`DB_HOST=db`, `REDIS_HOST=redis`)
- Persister les données MySQL via un **volume nommé** `db_data`
- Capturer les e-mails en dev avec **Mailpit** (Exo 5 newsletters)
- Lancer le **worker de queue** dans un conteneur dédié

## Architecture des services

```text
Navigateur → localhost:8080 → [nginx:80]
                                  ↓ fastcgi_pass app:9000
                              [app — PHP 8.4-FPM / Laravel]
                                  ↓                    ↓
                            [db — MySQL 8.4]      [redis — cache/sessions/queue]
                                  ↑
                            [queue — php artisan queue:work]
                            
[E-mails] Laravel → mailpit:1025 → UI http://localhost:8025
```

| Service | Image / build | Rôle | Port exposé |
|---------|---------------|------|-------------|
| `app` | `Dockerfile` (PHP 8.4-FPM) | Code Laravel, `php-fpm` | — (interne 9000) |
| `nginx` | `nginx:alpine` | Serveur web, `public/` | **8080** → 80 |
| `db` | `mysql:8.4` | Base MySQL persistante | — (interne 3306) |
| `redis` | `redis:7-alpine` | Cache, sessions, queues (Exo 4/5) | — |
| `mailpit` | `axllent/mailpit` | Capture SMTP dev (Exo 5) | **8025** (UI) |
| `queue` | même `Dockerfile` | Worker `queue:work` | — |

## Configuration `.env` (Exo 7)

| Variable | Valeur Docker | Rôle |
|----------|---------------|------|
| `DB_CONNECTION` | `mysql` | Pilote BDD (remplace SQLite local) |
| `DB_HOST` | `db` | Nom du service MySQL sur le réseau `laravel` |
| `DB_PORT` | `3306` | Port MySQL interne |
| `DB_DATABASE` | `laravel` | Doit correspondre à `MYSQL_DATABASE` dans compose |
| `DB_USERNAME` / `DB_PASSWORD` | `laravel` / `secret` | Utilisateur applicatif MySQL |
| `REDIS_HOST` | `redis` | Nom du service Redis |
| `REDIS_CLIENT` | `phpredis` ou `predis` | Client Redis (extension installée dans Dockerfile) |
| `QUEUE_CONNECTION` | `redis` | Files d'attente consommées par le conteneur `queue` |
| `MAIL_HOST` | `mailpit` | Serveur SMTP Mailpit (port 1025 interne) |
| `MAIL_PORT` | `1025` | Port SMTP Mailpit |
| `SESSION_DRIVER` / `CACHE_STORE` | `redis` | Sessions et cache (Exo 4) |

## Commandes (Exo 7)

| Commande | Rôle |
|----------|------|
| `docker compose up -d --build` | Construit l'image et démarre tous les services en arrière-plan |
| `docker compose down` | Arrête et supprime les conteneurs (volume `db_data` conservé) |
| `docker compose down -v` | Arrête + supprime les volumes (reset BDD) |
| `docker compose exec app composer install` | Installe `vendor/` dans le conteneur (volume écrase le build) |
| `docker compose exec app php artisan key:generate` | Génère `APP_KEY` |
| `docker compose exec app php artisan migrate` | Applique les migrations sur MySQL |
| `docker compose exec app php artisan db:seed --class=AdminSeeder` | Compte admin de test |
| `docker compose logs -f queue` | Suit les logs du worker de queue |
| `docker compose ps` | État des conteneurs |

## Fichiers du commit `6beacf4`

| Fichier | Rôle |
|---------|------|
| `Dockerfile` | Image PHP 8.4-FPM : extensions (pdo_mysql, redis, gd…), Composer, `php-fpm` |
| `docker-compose.yml` | Définition des 6 services, volumes, réseau, healthcheck MySQL |
| `docker/nginx/default.conf` | Vhost Nginx : `try_files`, FastCGI vers `app:9000` |
| `docker/php/local.ini` | Limites PHP : upload 50M, mémoire 256M |
| `.dockerignore` | Exclut `.git`, `vendor`, `.env`, logs du contexte de build |

### Détail : `Dockerfile`

| Étape | Comportement |
|-------|--------------|
| `FROM php:8.4-fpm` | Image officielle PHP en mode FastCGI |
| `docker-php-ext-install` | `pdo_mysql`, `zip`, `gd`, `opcache`, `mbstring`, `pcntl` |
| `pecl install redis` | Extension Redis native (cache/queue) |
| `COPY --from=composer` | Binaire Composer disponible dans le conteneur |
| `composer install --no-dev` | Dépendances prod au build (réinstallées en dev via volume) |
| `USER www-data` | php-fpm non-root |
| `CMD php-fpm` | Écoute sur le port 9000 |

### Détail : `docker-compose.yml`

| Service | Point clé |
|---------|-----------|
| `app` | `depends_on: db: condition: service_healthy` — attend MySQL |
| `nginx` | Volume `default.conf` + code source monté |
| `db` | Volume `db_data` + variables `MYSQL_*` depuis `.env` |
| `queue` | Même image que `app`, commande `queue:work --tries=3` |
| `mailpit` | Port 8025 pour consulter les e-mails envoyés |

### Détail : `docker/nginx/default.conf`

- `root /var/www/html/public` — point d'entrée Laravel
- `try_files $uri $uri/ /index.php?$query_string` — routing front controller
- `fastcgi_pass app:9000` — liaison vers PHP-FPM

## Premier lancement (checklist)

1. Configurer `.env` avec les hôtes Docker (`db`, `redis`, `mailpit`)
2. `docker compose up -d --build`
3. `docker compose exec app composer install` *(le volume local écrase `vendor/` du build)*
4. `docker compose exec app php artisan key:generate && php artisan migrate`
5. Ouvrir `http://localhost:8080` — login admin
6. Envoyer une newsletter → vérifier l'e-mail dans `http://localhost:8025`
7. Le conteneur `queue` traite les jobs sans `php artisan queue:work` local

## Dépendances des exercices précédents

- **Exo 4** — Redis pour sessions/cache (`REDIS_HOST=redis`)
- **Exo 5** — Queue Redis + e-mails (worker `queue`, Mailpit)
- **Exo 3+** — MySQL remplace SQLite ; migrations Fortify/Sanctum/posts/newsletters

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
- **Laravel Horizon** — dashboard de monitoring des queues Redis (Exo 5)
- **Mailable** (`NewsletterMail`) — e-mail encapsulé avec vue Blade dédiée (Exo 5)
- **Jobs / Queue** (`SendNewsletterJob`) — traitement asynchrone via queue Redis (Exo 5)
- **Notifications** (`NewsletterSentNotification`) — confirmation admin après envoi (Exo 5)
- **Pest** — tests Feature avec syntaxe `it()` / `expect()` (Exo 6)
- **Laravel Dusk** — tests Browser Chrome (login Fortify, assertions DOM) (Exo 6)
- **Factories Eloquent** — données de test reproductibles (Exo 6)
- **Mail::fake** / **Notification::fake** — tests du job newsletter sans envoi réel (Exo 6)
- **Docker Compose** — PHP-FPM, Nginx, MySQL, Redis, Mailpit, worker queue (Exo 7)
- **Mailpit** — capture et prévisualisation des e-mails en développement (Exo 7)

---

## Licence

MIT — voir le framework [Laravel](https://laravel.com).
