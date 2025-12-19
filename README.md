# St. Alphonsus Primary School Management System

> A role-based administration portal for managing students, teachers, and classes, built with secure PHP and MySQL.

# About
This project is a comprehensive School Management System designed to digitize the administrative workflow of a primary school. It features a secure login system with **Role-Based Access Control (RBAC)**, allowing Admins, Teachers, and Parents to access specific dashboards and data privacy layers.

## Key Features
* **Role-Based Access Control:** Distinct dashboards for Admins, Teachers, and Parents.
* **Secure Authentication:** Uses `password_hash()` and `password_verify()` for industry-standard encryption.
* **CRUD Operations:** Full management of Student, Teacher, and Parent records.
* **Responsive Design:** Built with **Bootstrap 5.3** for mobile-friendly access.
* **SQL Injection Protection:** All database queries utilize **Prepared Statements**.

## Tech Stack
* **Backend:** PHP 8+ (Procedural)
* **Frontend:** HTML5, CSS3, Bootstrap 5.3.8
* **Database:** MySQL / MariaDB
* **Security:** BCrypt Hashing, Prepared Statements

## Getting Started

### Prerequisites
* XAMPP, WAMP, or any PHP server environment.
* MySQL Database.

### Installation
1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/Parachichi066/st-alphonsus-primary-school.git](https://github.com/Parachichi066/st-alphonsus-primary-school.git)
    ```
2.  **Database Setup:**
    * Open phpMyAdmin (or your SQL client).
    * Create a database named `st_alphonsus`.
    * Import the `database/st_alphonsus.sql` file provided in this repo.
3.  **Configure Connection:**
    * Check `connection.php` to ensure the database credentials match your local setup.
4.  **Run:**
    * Place the project folder in your `htdocs` (XAMPP) or `www` (WAMP) directory.
    * Navigate to `localhost/st-alphonsus-primary-school` in your browser.

## Login Credentials (Test Data)
The system comes pre-populated with users for testing purposes.

| Role | Username | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `admin` | Full CRUD access to all records |
| **Parent** | `d.garcia` | `parent123` | View child's report/attendance |
| **Teacher** | `r.adams` | `teacher123` | View students in class |

> **Note:** You can register new users via `register.php`.

## Security Highlights
* **Password Hashing:** Passwords are never stored in plain text. We use PHP's native `password_hash` algorithm.
* **Input Validation:** Server-side validation ensures data integrity before touching the database.
