# Hospital Management System

A PHP/MySQL hospital appointment system with three separate role areas: patients book appointments
with a named doctor, doctors work through their own queue and write medical history, and an admin
manages doctors, specialisations and reporting. Originally a student project; since hardened with
prepared statements, bcrypt password hashing and CSRF tokens.

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
![PHP 8.2](https://img.shields.io/badge/PHP-8.2%20(CI)-777bb3)

## Features

- Patient registration with live email-availability checking (`check_availability.php`, AJAX).
- Appointment booking by specialisation, doctor, date and time, with server-side date validation.
- Doctor area: assigned appointments, appointment history, patient records, profile editing.
- Medical history records written by doctors and readable by the patient
  (`manage-medhistory.php`, `view-medhistory.php`).
- Admin area: manage doctors, doctor specialisations, patients, users and contact queries.
- Date-range appointment reporting (`between-dates-reports.php`,
  `betweendates-detailsreports.php`).
- Login audit trails for both doctors and users (`doctorslog`, `userlog` tables).
- Password reset gated on matching full name and registered email
  (`forgot-password.php`, `reset-password.php`).

## Security notes

The upstream project was written the way most PHP coursework is written: string-concatenated SQL and
MD5 passwords. Those are the parts worth reading in this repo, because they are the parts that were
changed.

- **Prepared statements.** Query paths use `mysqli_prepare` + `bind_param` rather than interpolation.
- **Credentials out of source.** `include/config.php` reads `DB_SERVER` / `DB_USER` / `DB_PASS` /
  `DB_NAME` from the environment, falling back to local-dev defaults. `.env` is gitignored.
- **Errors are logged, not printed.** A failed connection writes to `error_log` and shows the browser
  a generic message, so DSNs and SQL text do not leak to users.
- **bcrypt with an MD5 fallback.** `include/security.php` verifies bcrypt hashes first and falls back
  to MD5 only for legacy rows; `needs_rehash()` lets a successful legacy login be upgraded in place.
  This was chosen over a hard cutover because the seed database ships MD5 rows and a cutover would
  lock every existing account out.
- **CSRF tokens.** `csrf_token()` / `csrf_field()` / `csrf_verify()` using `random_bytes` and
  `hash_equals`.

This is a learning codebase, not a production clinical system. Do not put real patient data in it.

## Architecture

```
browser
  |
  +-- hospital/index.php ................ public landing page + contact form -> tblcontactus
  |
  +-- hospital/hms/ ..................... patient area (16 PHP entry points)
  |     user-login.php, registration.php, book-appointment.php,
  |     appointment-history.php, view-medhistory.php, edit-profile.php
  |
  +-- hospital/hms/doctor/ .............. doctor area (14 PHP entry points)
  |     dashboard.php, manage-patient.php, add-patient.php, search.php
  |
  +-- hospital/hms/admin/ ............... admin area (25 PHP entry points)
        manage-doctors.php, doctor-specilization.php, manage-users.php,
        between-dates-reports.php, doctor-logs.php, unread-queries.php

hospital/hms/include/
  config.php    mysqli connection, env-driven credentials, utf8mb4
  checklogin.php session guard, redirects to user-login.php
  security.php  CSRF, password hashing, input sanitisation
  header.php / sidebar.php / footer.php  shared chrome

hospital/hms/vendor/   front-end libraries vendored in (Bootstrap, jQuery,
                       DataTables, fullcalendar, Chart.js, select2, ...)
```

Database: 11 MySQL tables — `admin`, `users`, `doctors`, `doctorspecilization`, `appointment`,
`tblpatient`, `tblmedicalhistory`, `tblcontactus`, `tblpage`, `doctorslog`, `userlog`.

## Quickstart

Requires PHP, MySQL and Apache — XAMPP, WAMP or LAMP all work.

```bash
# 1. Place the app under your web root
#    XAMPP: C:\xampp\htdocs\hospital
#    WAMP:  C:\wamp\www\hospital
#    LAMP:  /var/www/html/hospital
cp -r hospital /var/www/html/

# 2. Create the database and import the schema + seed data
mysql -u root -p -e "CREATE DATABASE hms"
mysql -u root -p hms < "SQL File/hms.sql"

# 3. Configure credentials
cp .env.example .env
```

`.env.example` documents the four variables read by `include/config.php`:

```
DB_SERVER=localhost
DB_USER=root
DB_PASS=your_db_password_here
DB_NAME=hms
```

Then open `http://localhost/hospital`.

## Seed logins

These come from the seed data in `SQL File/hms.sql`. They are demo accounts for a local install.

| Role | Username / email | Password |
|------|------------------|----------|
| Admin | `admin` | `Test@12345` |
| Patient | `johndoe12@test.com` | `Test@123` |
| Doctor | `anujk123@test.com` | `Test@123` |

## Usage

Booking an appointment posts to `hospital/hms/book-appointment.php`, which validates and inserts via
a prepared statement:

```php
$stmt = mysqli_prepare($con,
    "INSERT INTO appointment
     (doctorSpecialization, doctorId, userId, consultancyFees,
      appointmentDate, appointmentTime, userStatus, doctorStatus)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
```

`get_doctor.php` returns the doctors for a chosen specialisation, so the doctor dropdown can be
populated without a page reload.

## Tech stack

PHP (procedural, `mysqli`) · MySQL · Bootstrap 3 · jQuery · SCSS (`hospital/hms/master/sass`,
compiled with the Ruby `config.rb`) · Apache.

## Continuous integration

`.github/workflows/ci.yml` runs `php -l` across every `.php` file on PHP 8.2. It is a syntax gate
only — there is no test suite in this repository.

## License

MIT — see [LICENSE](LICENSE).
