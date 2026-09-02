# auditors3.lv

Laravel 10 application with Docker (Laravel Sail) development environment.

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) or Docker Engine + Docker Compose

## Quick Start with Docker

### 1. Environment Configuration

Copy the example environment file if you haven't already:

```bash
cp .env.example .env
```

Key environment configurations for Docker:
- **Application Port (`APP_PORT`)**: `84` (Accessible at `http://localhost:84`)
- **Vite Dev Server Port (`VITE_PORT`)**: `5174`
- **MySQL External Port (`FORWARD_DB_PORT`)**: `3302` (Internally `3306` on host `mysql`)
- **Mailpit Web UI (`FORWARD_MAILPIT_DASHBOARD_PORT`)**: `8025` (Accessible at `http://localhost:8025`)
- **Mailpit SMTP Port (`FORWARD_MAILPIT_PORT`)**: `1025`

### 2. Build and Start Containers

Start all containers in detached mode:

```bash
docker compose up -d
```
*(or using Laravel Sail: `./vendor/bin/sail up -d`)*

### 3. Install Dependencies (First Run)

If you haven't installed Composer or NPM dependencies yet, run inside the container:

```bash
docker compose exec laravel.test composer install
docker compose exec laravel.test npm install
```

### 4. Database Setup & Migrations

Run database migrations:

```bash
docker compose exec laravel.test php artisan migrate
```

Run seeders (if applicable):

```bash
docker compose exec laravel.test php artisan db:seed
```

### 5. Frontend Assets

For local development with Hot Module Replacement (HMR):

```bash
docker compose exec laravel.test npm run dev
```

Or to build production assets:

```bash
docker compose exec laravel.test npm run build
```

---

## Common Docker Commands

| Action | Command |
|---|---|
| **Start Containers** | `docker compose up -d` |
| **Stop Containers** | `docker compose down` |
| **Restart Stack** | `docker compose restart` |
| **View Logs** | `docker compose logs -f` |
| **Container Shell** | `docker compose exec laravel.test bash` |
| **Artisan Command** | `docker compose exec laravel.test php artisan <command>` |
| **Tinker Console** | `docker compose exec laravel.test php artisan tinker` |
| **Run Tests** | `docker compose exec laravel.test php artisan test` |

---

## Services & Ports

- **Web App**: [http://localhost:84](http://localhost:84)
- **Vite Dev Server**: [http://localhost:5174](http://localhost:5174)
- **Mailpit Email Inspector**: [http://localhost:8025](http://localhost:8025)
- **MySQL Database**: `localhost:3302` (User: `sail`, Password: `password`, Database: `laravel`)
