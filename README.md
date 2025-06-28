# Hospital Management System

A web-based hospital appointment management system where patients can browse doctors by specialisation, select a date and time, and book appointments. Includes dedicated dashboards for patients, doctors, and administrators.

## Features

**Patients**
- Register, log in, and manage profile
- Browse doctors by specialisation
- Book, view, and track appointment history
- Manage medical history and records
- Password and email management

**Doctors**
- View assigned appointments and history
- Add and manage patient medical records
- Edit professional profile

**Admin**
- Full dashboard with system overview
- Add/manage doctors and specialisations
- View and manage all appointments
- Patient and user management
- Generate appointment reports by date range
- View activity logs and contact queries

## Tech Stack
| Layer | Technology |
|-------|-----------|
| Backend | PHP 7+ |
| Database | MySQL |
| Frontend | HTML, CSS, SCSS, JavaScript |
| Server | Apache (XAMPP / WAMP / LAMP) |

## Setup

### Prerequisites
- XAMPP, WAMP, or LAMP stack
- PHP 7+
- MySQL

### Installation

1. Clone or download this repository
2. Copy the `hospital` folder to your web server root:
   - XAMPP: `C:\xampp\htdocs\hospital`
   - WAMP: `C:\wamp\www\hospital`
   - LAMP: `/var/www/html/hospital`
3. Open **phpMyAdmin** at `http://localhost/phpmyadmin`
4. Create a database named `hms`
5. Import `SQL File/hms.sql`
6. Open `hospital/hms/include/config.php` and set your DB credentials:

```php
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hms');
```

7. Visit `http://localhost/hospital` in your browser

## Demo Credentials
| Role | Email | Password |
|------|-------|----------|
| Admin | admin | Test@12345 |
| Patient | johndoe12@test.com | Test@123 |
| Doctor | anujk123@test.com | Test@123 |

## Project Structure
```
Hospital-Management-System/
├── SQL File/
│   └── hms.sql                 # Database schema and seed data
└── hospital/
    ├── index.php               # Landing page
    └── hms/
        ├── admin/              # Admin panel (26 PHP files)
        ├── doctor/             # Doctor panel (15 PHP files)
        ├── include/            # Shared config, auth, header, footer
        ├── book-appointment.php
        ├── registration.php
        ├── user-login.php
        └── dashboard.php
```
