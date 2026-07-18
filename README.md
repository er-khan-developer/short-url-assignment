# Short URL Assignment

A multi-tenant URL shortener built with Laravel, Vue 3, Inertia.js, and TypeScript. SuperAdmin users create companies and assign Admins; Admins invite Members; Admins and Members generate short URLs scoped to their company. Authentication and authorization use a custom `role` field on the `users` table—no external role/permission package.

## Features

- **Company management** — SuperAdmin can create companies and assign an Admin user
- **User invitations** — SuperAdmin and Admin can invite Admin/Member users via email
- **Short URL generation** — Admin and Member users can create short links for their company
- **Short URL redirect** — Public redirect endpoint resolves short links to the original URL
- **Role-based dashboards** — Company and short URL listings filtered by role
- **Responsive UI** — DataTables-based listings with Inertia pagination and toast notifications

## Tech Stack

| Layer | Technologies |
| --- | --- |
| Backend | PHP 8.3, Laravel 12 |
| Frontend | Vue 3, TypeScript, Inertia.js v3 |
| Styling | Tailwind CSS v4 |
| Data tables | DataTables.net with Vue 3 integration |
| HTTP | Axios |
| Notifications | Vue Sonner |
| Database | SQLite (default), MySQL supported |

### Business Rules

1. **Add Company (SuperAdmin only)** — Creates a company and links the provided email as the company Admin. If the user does not exist, one is created with a random password and `Admin` role.
2. **Invite Users** — SuperAdmin and Admin can invite users with role `Admin` or `Member`. An invitation email is sent with an acceptance link.
3. **Accept Invitation** — Visiting the invite link creates the user (if new), links them to the company, and emails login credentials.
4. **Generate Short URL** — Admin and Member users submit a valid URL; a unique short code is stored and linked to the user and company.
5. **Short URL access** — `GET /company/s/{shortUrl}` redirects to the original URL without authentication.
6. **Short URL listing** — Members see only their own links; Admins see links from users in their companies; SuperAdmin sees links they created (SuperAdmin cannot create short URLs).

## 📸 Some Application Screenshots 

### Company Listing page
![Company Listing Page ] (https://prnt.sc/GwhIElp8G1ch)

### Invite User Page 
![ Invite User Page ] (https://prnt.sc/IiLafz4-FqVD)


### Add Company Page 
![Add Company Page ] (https://prnt.sc/RLfU0VGITOE7)

### Short Url Page 
![Short Url Page ] (https://prnt.sc/356ZSaVSMCAt)

### Generate Url Page 
![Generate Url Page ] (https://prnt.sc/7pX88cKK9P5Z)

### Invite Email Page 
![Invite Email Page ] (https://prnt.sc/cQNpCXZ2HqBL)

### User Email Page after invitation accepted 
![User Email Page ] (https://prnt.sc/39FeEp8z3vPu)

## Project Structure

```
short-url-assignment/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CompanyController.php
│   │   │   └── InvitationController.php
│   │   └── Requests/
│   │       ├── AddCompanyRequest.php
│   │       ├── GenerateShortUrlRequest.php
│   │       └── InviteUserRequest.php
│   ├── Models/
│   │   ├── Company.php
│   │   ├── CompanyUser.php
│   │   ├── Invitation.php
│   │   ├── ShortUrl.php
│   │   └── User.php
│   └── Services/
│       └── CompanyService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── SuperAdminSeeder.php
├── resources/js/
│   ├── components/
│   │   ├── GenerateShortUrlModal.vue
│   │   └── InviteModal.vue
│   ├── layouts/
│   │   └── AppLayout.vue
│   └── pages/
│       ├── Dashboard.vue
│       └── ShortUrl.vue
├── routes/
│   └── web.php
└── tests/Feature/
```

## Prerequisites

- PHP 8.3+
- Composer
- Node.js 18+ and npm
- SQLite (enabled) or MySQL

## Installation

### Quick setup

```bash
composer setup
```

This installs PHP and npm dependencies, creates `.env`, generates the app key, runs migrations, and builds frontend assets.

### Manual setup

```bash
# Installation 

git clone https://github.com/er-khan-developer/short-url-assignment.git

## Copy .env file 
cp .env.example .env

composer install

# key generate
php artisan key:generate

## npm install 
npm install


# Database (Mysql is default) set the credential or change .env accordingly

touch database/database.sqlite
php artisan migrate

# Seed SuperAdmin user
php artisan db:seed --class=SuperAdminSeeder

# Frontend build
npm run build
```

### Default SuperAdmin credentials

After running `SuperAdminSeeder`:

| Field | Value |
| --- | --- |
| Email | `superadmin@test.com` |
| Password | `12345678` |

## Running the Application

### Development (recommended)

Starts the Laravel server, queue worker, and Vite dev server together:

```bash
composer dev
```

### Individual commands

```bash
# Backend
php artisan serve

# Frontend (separate terminal)
npm run dev
```

## Routes & API Endpoints

| Method | Route | Description | Auth |
| --- | --- | --- | --- |
| `GET` | `/` | Redirect to dashboard or login | — |
| `GET` | `/dashboard` | Company listing page | Yes |
| `POST` | `/company/store` | Create company (SuperAdmin) | Yes |
| `POST` | `/company/{companyId}/invite` | Send user invitation | Yes |
| `POST` | `/company/{companyId}/generate-short-url` | Create short URL | Yes |
| `GET` | `/company/short-url` | Short URL listing page | Yes |
| `GET` | `/company/s/{shortUrl}` | Redirect to original URL | No |
| `GET` | `/invite/{token}` | Accept invitation | No |

JSON endpoints (`/company/store`, `/company/{companyId}/invite`, `/company/{companyId}/generate-short-url`) return `{ success, message }` and use standard HTTP status codes (201, 403, 422, 500).

## Frontend Pages

### Dashboard (`/dashboard`)

- Paginated company DataTable
- **Add Company** button (SuperAdmin only)
- Per-row **Invite Users** (SuperAdmin/Admin)
- Per-row **Generate Short Url** (Admin/Member)

### Short URLs (`/company/short-url`)

- Paginated short URL DataTable
- Clickable short links open `{APP_URL}/company/s/{code}`
- Admin and SuperAdmin also see **Generated By** and **Company Name** columns

## Testing

```bash
# Full test suite (Pint, PHPStan, PHPUnit)
composer test

# PHPUnit only
php artisan test
```

Feature tests cover:

- Admin and Member can create short URLs
- SuperAdmin cannot create short URLs
- Members see only their own short URLs
- Short URL redirect resolves to the original URL


## Mail Configuration

Invitations and credential emails use Laravel Mail. For local development, `.env.example` sets `MAIL_MAILER=log`, so messages are written to `storage/logs/laravel.log`.

# 📝 Development Notes & AI Usage Disclaimer

> **Note:** This project was developed as part of an assignment.

- The business logic and overall application flow were designed and implemented based on my own understanding and development experience.
- AI tools were **not** used to implement the application's business logic and workflow.
- AI assistance was used only for:
  - Debugging issues
  - Installing packages (e.g., DataTables and Toast notifications)
  - Creating and improving the `README.md` documentation
- The project implements only the functionality required for the assignment.
- Advanced business logic, scalability, security hardening, and future enhancements were intentionally kept out of scope.
- The application demonstrates a clean, functional implementation that satisfies the assignment requirements.


