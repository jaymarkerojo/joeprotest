# JoePro - Laravel Canyoneering Booking System

Laravel 13 + Tailwind CSS canyoneering booking website with MySQL. The current release represents approximately 60% of the planned system.

## Included modules

- **Package module:** package browsing, inclusions, rates, guided booking form, live cost estimate, and operator package management.
- **Booking module:** tourist booking history/cancellation and operator approval/rejection dashboard.
- **Authentication:** public tourist registration plus role-based tourist and operator sign-in. Operator accounts cannot be created from the public registration form.
- **ERD support:** transports, payments, notifications, reviews, and admin review replies based on the supplied FigJam ERD.

## Run in VS Code / Laragon

1. Open this `canyon` folder in VS Code.
2. Start Apache and MySQL in Laragon.
3. Run `composer install` and `npm install`.
4. Copy `.env.example` to `.env`, then run `php artisan key:generate`.
5. Run `php artisan migrate:fresh --seed`.
6. Run `npm run build`.
7. Run `php artisan serve`, then open `http://localhost:8000`.

The local `joepro_laravel` database is already created and seeded on this machine.

New registrations are saved immediately in the MySQL `joepro_laravel.users` table and can be viewed in phpMyAdmin. Every public registration is forced to the `tourist` role by the server, including if someone modifies the form request.

## Development accounts

- Tourist: `tourist@joepro.test` / `Tourist123!`
- Operator: `operator@joepro.test` / `Operator123!`

## Direct MySQL import

If you prefer phpMyAdmin or MySQL Workbench instead of Laravel migrations, import `database/sql/joepro_mysql.sql`. It creates the `joepro_laravel` database, ERD tables, packages, transports, bookings, and the development accounts.

## Test on another laptop safely

1. Install Laragon and Git, then start Apache and MySQL.
2. Clone this repository and open the cloned folder in VS Code.
3. Run `composer install` and `npm install`.
4. Copy `.env.example` to `.env` and run `php artisan key:generate`.
5. In phpMyAdmin, create a new empty database named `joepro_laravel`.
6. Confirm `.env` uses `DB_CONNECTION=mysql`, `DB_DATABASE=joepro_laravel`, and the correct local MySQL username/password.
7. Run `php artisan migrate:fresh --seed`, `php artisan storage:link`, and `npm run build`.
8. Run `php artisan serve` and open `http://127.0.0.1:8000`.

This produces the same table structure on the second laptop while keeping each laptop's records separate. Only run `migrate:fresh --seed` on a new/test database because it intentionally clears that selected database. For an existing database with real records, use `php artisan migrate --force` instead.
