# MiniEcom MVC

MiniEcom MVC is an e-commerce web application built with Laravel, with two main areas:
- Client: customer-facing pages (products, cart, checkout, account, wishlist).
- Admin: management for categories, products, orders, posts, banners, coupons, and users.

## Tech Stack

- PHP `^8.2`
- Laravel `^12.0`
- MySQL or SQLite
- Vite `^7`
- Tailwind CSS `^4`

## Environment Requirements

- PHP >= 8.2
- Composer
- Node.js + npm
- Database server (MySQL is recommended for real usage)

## Project Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

## Database Configuration

Open `.env` and update:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations and seeders:

```bash
php artisan migrate --seed
```

## Run the Project (Development)

Run backend:

```bash
php artisan serve
```

Run frontend:

```bash
npm install
npm run dev
```

Open the app at:
- `http://127.0.0.1:8000`

## Default Accounts

Admin/user accounts depend on seeded data in `database/seeders`.
If needed, check or edit:
- `database/seeders/UserSeeder.php`

## Useful Commands

```bash
php artisan route:list
php artisan test
npm run build
```

## Main Directory Structure

- `app/Http/Controllers/Admin`: admin controllers
- `app/Http/Controllers/Client`: client controllers
- `resources/views/admin`: admin views
- `resources/views/client`: client views
- `routes/web.php`: web routes

## Notes

- If you face login/logout or session issues, check `SESSION_*` variables in `.env`.
- If using Laragon/XAMPP, make sure MySQL is running before performing database operations.
