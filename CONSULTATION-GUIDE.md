# JoePro Consultation Guide

## What is finished

The prototype demonstrates two complete, connected modules using Laravel, Tailwind CSS, and MySQL.

### 1. Package module

- A tourist opens **Packages**.
- Laravel reads active packages from the `packages` table.
- Each package shows its rate, location, inclusions, duration, and capacity.
- A signed-in tourist selects a tour date and number of guests.

Main files:

- `routes/web.php` - URL definitions.
- `resources/views/packages/index.blade.php` - Package interface.
- `app/Models/Package.php` - Package database model.
- `database/migrations/2026_09_06_000200_create_packages_table.php` - Table structure.

### 2. Booking module

- The tourist submits a booking request.
- `BookingController` validates the form and checks remaining capacity inside a database transaction.
- The tourist sees the request in **My bookings** and can cancel it while pending.
- An operator opens **Booking desk** and approves or rejects the same record.
- Dashboard totals update from MySQL data.

Main files:

- `app/Http/Controllers/BookingController.php` - Tourist actions.
- `app/Http/Controllers/OperatorController.php` - Operator actions.
- `resources/views/bookings/index.blade.php` - Tourist booking history.
- `resources/views/operator/index.blade.php` - Operator dashboard.
- `database/migrations/2026_09_06_000300_create_bookings_table.php` - Booking table.

## How the Laravel request works

1. The browser sends a request to a URL.
2. `routes/web.php` chooses a controller method.
3. The controller validates the input and uses an Eloquent model.
4. The model reads or updates MySQL.
5. The controller returns a Blade view or redirects to another page.
6. Tailwind classes style the Blade page.

## How to run it

1. Start **MySQL** in Laragon.
2. Open the `canyon` folder in VS Code.
3. In the VS Code terminal, run:

   ```bash
   composer install
   npm install
   php artisan migrate:fresh --seed
   npm run build
   php artisan serve
   ```

4. Open `http://127.0.0.1:8000`.

If you use phpMyAdmin or MySQL Workbench, you may import `database/sql/joepro_mysql.sql` instead of running the migration command.

## Accounts for the demonstration

| Role | Email | Password |
| --- | --- | --- |
| Tourist | `tourist@joepro.test` | `Tourist123!` |
| Operator | `operator@joepro.test` | `Operator123!` |

## Suggested professor demonstration

1. Show the Home and Packages pages.
2. Log in with the tourist account.
3. Create a Standard Package booking for a future date.
4. Open My Bookings and point out its **Pending** status.
5. Log out and sign in with the operator account.
6. Open Booking Desk and approve the new request.
7. Sign back in as the tourist to show the updated **Approved** status.
8. Open the MySQL `bookings` table to prove the status is persisted.

## ERD implementation

The supplied ERD guided the following tables: `users`, `packages`, `transports`, `bookings`, `payments`, `notifications`, `reviews`, and `admin_review_replies`. Laravel uses numeric primary keys and one `users` table with a `role` field so its built-in authentication can serve both tourists and operators cleanly.

## Run the automated checks

```bash
php artisan test
```

The feature tests verify that a tourist can create a booking and an operator can approve it.
