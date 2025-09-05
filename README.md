# InternHub - Internship Application & Tracking System

A simple full-stack web application for Internship application and tracking, developed using native PHP, MySQL, HTML, CSS, and JavaScript.

## ToDo

- Setup Database
- Login Page
- Admin Page
- Homepage (default)
- Homepage (user logged in)
- Help Page
- Functionalities Page
- Analytical reports
- Feedback forms

## Setup

### 1. Setup the environment

- **Recomended** - Install XAMPP v8.2.4 (All-in-one utility with all the required dependencies)

- Using command-line interface

    1. Install httpd (apache server)
    2. Install php
    3. Install mysql
    4. Install phpmyadmin

### 2. Initialize Database

1. Access phpmyadmin through the browser `localhost/phpmyadmin`

2. Go to "Import" tab and choose the `schema.sql` file

- Now your tables are created and populated with the default users and information

- If you want to test the analytical reports, you can,
    - from the terminal, navigate to the `db/seeding` folder and execute the `seed-analytics-data.php` file
    - from the browser, login as admin and navigate to `localhost/internhub/db/seeding/seed-analytics-data.php`

### 3. Login

#### Default Users
    - Admin
        - username: admin
        - password: admin
        

    - Student
        - username: uoc
        - password: uoc
    - Company
        - username: codalyth
        - password: codalyth