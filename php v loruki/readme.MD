# Updated Loruki Website with PHP and SQL

This update includes a sign-in, log-in, and log-out function, all coded in PHP. I highly recommend experimenting by creating your own database and SQL tables.

## Setting Up Your Own Database
**Note**: This setup was done using XAMPP localhost.

### 1. Create a New Database
In phpMyAdmin (XAMPP):

1. Open [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
2. Click **Database** > Enter a name to create a new database (e.g., `my_custom_db`; in my case, it was called `loruki`).
3. Click **Create**.

*(For other database types, adjust accordingly.)*

### 2. Define Your Tables

#### Users Table (Stores registered users)
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(225) NOT NULL
);
```

#### User Plans Table (Stores created user plans)
```sql
CREATE TABLE user_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    storage INT NOT NULL,
    bandwidth INT NOT NULL,
    description TEXT NOT NULL,
    add_ons VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

*(If using XAMPP, click "Go". If using PostgreSQL, SQL Server, or SQLite, adjust the SQL syntax accordingly.)*

### 3. Update the `config.php` File
View the updated [`config.php`](https://github.com/1501henify/Loruki-with-database/blob/myBranch/users/php/config.php) file for any database modifications.

---

## Features ✌🏾
- Responsive website, supports any screen size.
- Sign-in/Log-in Authentication.
- Stores user input such as passwords, emails, usernames, and created plans.
- Security-conscious password encryption using PHP's `MD5` and `password_verify` functions.
- This `README.md` file 🤧

---

## Technologies Used
- PHP (Using HTML5 Syntax)
- CSS3
- SQL
- XAMPP Localhost
- GitHub ✌🏾

---

**https://loruki.page.gd**

## Clone This Repository
To download these files to your local machine, run the following command:

```bash
git clone https://github.com/1501henify/Loruki-with-database
