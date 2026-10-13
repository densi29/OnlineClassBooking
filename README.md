# Online Class Scheduling and Booking System

## Project Overview

The Online Class Scheduling and Booking System is a web-based application developed using HTML5, CSS3, JavaScript, PHP, and MySQL/MariaDB. It allows students to register, log in, browse available classes, book classes, view their bookings, and cancel bookings. Administrators can log in, add new classes, and view student bookings.

The project is designed to simplify class scheduling and make the booking process easier for students and administrators.

## Features

### Student Features
- Student registration and login
- Password hashing
- Browse upcoming classes
- View class details, including teacher, date, time, and capacity
- Book available classes
- View personal bookings
- Cancel bookings
- Prevention of duplicate bookings

### Administrator Features
- Administrator login
- Add new classes
- View student bookings
- Manage class scheduling through the admin interface

## Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Local Server:** XAMPP (Apache)
- **Code Editor:** Visual Studio Code
- **Version Control:** Git and GitHub

## Project Structure

```text
OnlineClassBooking/
├── admin/
│   ├── add_class.php
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   └── view_bookings.php
├── assets/
│   └── style.css
├── config/
│   └── db.php
├── database/
│   ├── schema.sql
│   └── online_class_booking.sql  (local export; review before sharing)
├── student/
│   ├── book_class.php
│   ├── classes.php
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── my_bookings.php
│   └── register.php
├── .gitignore
├── index.php
└── README.md
```

## Requirements

- Windows, Linux, or macOS
- XAMPP with Apache, PHP, and MySQL/MariaDB
- A web browser
- Visual Studio Code (recommended)

## Installation and Setup

### 1. Install XAMPP

Install XAMPP and start Apache and MySQL/MariaDB. If you already have another MySQL installation on your computer, ensure the database server used by this project is running and accessible.

### 2. Copy the project

Place the `OnlineClassBooking` folder inside the XAMPP `htdocs` directory. For a standard Windows installation, the path is:

```text
C:\xampp\htdocs\OnlineClassBooking
```

### 3. Create the database and tables

1. Open phpMyAdmin at [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Click the **SQL** tab.
3. Open `database/schema.sql` in a text editor and copy its complete contents.
4. Paste the SQL into phpMyAdmin's SQL editor and click **Go**.

The script creates the `online_class_booking` database and the `students`, `classes`, and `bookings` tables, including their indexes and foreign-key relationships.

**Important:** The schema creates the database structure only. It does not create student accounts, administrator accounts, or sample classes. Register a student account through the application and add classes through the administrator interface.

If you already have a working database with the same tables, do not run setup scripts unnecessarily. Back up your data before making database changes.

### 4. Configure the database connection

Open `config/db.php` and check the connection settings for your local environment. The default XAMPP development setup may use:

- Host: `localhost`
- Database: `online_class_booking`
- Username: `root`
- Password: empty, if that is how your local XAMPP database is configured

Use the credentials configured on your own computer. Do not use an empty database password in a public or production deployment.

### 5. Open the application

Visit the home page:

[http://localhost/OnlineClassBooking/](http://localhost/OnlineClassBooking/)

## How to Use

### Student

1. Open the application in a browser.
2. Register a student account.
3. Log in using the registered credentials.
4. Browse available classes and book a class.
5. View or cancel bookings from the My Bookings page.

### Administrator

1. Open the administrator login page:

   [http://localhost/OnlineClassBooking/admin/login.php](http://localhost/OnlineClassBooking/admin/login.php)

2. Log in with the administrator credentials configured in the application.
3. Open the admin dashboard.
4. Add classes and view student bookings.

If the application uses demo administrator credentials, change them before any public deployment.

## Database

The application uses the `online_class_booking` database with three main tables:

- `students` stores student account information and hashed passwords.
- `classes` stores class details and capacity.
- `bookings` records student bookings and prevents duplicate bookings for the same class.

The `database/schema.sql` file contains the database structure without student or booking records.

A full database export may contain personal information and password hashes. Keep such exports local unless they have been reviewed and sanitized before sharing.

## Security Note

This project is intended for local development and educational use. Before deploying it publicly, review authentication, session security, access controls, database credentials, administrator account management, and other security settings. Do not publish real passwords, password hashes, student records, or sensitive configuration details.

## Future Improvements

- Search and filter classes
- Email booking confirmations
- Improved administrator account management
- Enhanced reporting and booking statistics
- Responsive design refinements

## Author

Developed as an educational web development project.

## License

This project is intended for educational purposes.
