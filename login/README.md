# Login System

A simple PHP-based login and registration system with MySQL database.

## Features

- User registration with name, email, and password
- User login with email and password
- Session management
- Responsive design
- Success/error messages
- Welcome page with user's name

## Setup Instructions

### 1. Database Setup

1. Start XAMPP and ensure Apache and MySQL services are running
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `login`
4. Import the `database_setup.sql` file or run the SQL commands manually:

```sql
CREATE DATABASE IF NOT EXISTS login;
USE login;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. File Setup

1. Place all PHP files in your XAMPP `htdocs` folder
2. Ensure the `connect.php` file has correct database credentials:
   - Host: localhost
   - Username: root
   - Password: (leave empty for default XAMPP setup)
   - Database: login

### 3. Access the Application

1. Open your web browser
2. Navigate to `http://localhost/your-folder-name/index.php`

## How It Works

### Registration (Sign Up)
1. Click "Sign up" button
2. Fill in your name, email, and password
3. Click "Sign up" to register
4. You'll see a success message: "Thank you [Name]! Registration successful."

### Login (Sign In)
1. Click "Sign in" button (name field will be hidden)
2. Enter your email and password
3. Click "Sign in" to login
4. If credentials are correct, you'll be redirected to the homepage
5. If incorrect, you'll see: "Incorrect email or password"

### Homepage
- Displays "Hi [Name]!" with a logout button
- Only accessible to logged-in users
- Automatically redirects to login page if not authenticated

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure XAMPP MySQL service is running
   - Check database credentials in `connect.php`
   - Verify database and table exist

2. **Registration Not Working**
   - Check if the `users` table exists
   - Ensure all form fields are filled
   - Check for duplicate email addresses

3. **Login Not Working**
   - Verify email and password are correct
   - Check if user exists in database
   - Ensure password was properly hashed during registration

4. **Session Issues**
   - Make sure `session_start()` is called
   - Check if cookies are enabled in browser

### File Structure
```
├── index.php          # Main login/registration page
├── register.php       # Handles registration and login logic
├── homepage.php       # Welcome page after successful login
├── logout.php         # Logout functionality
├── connect.php        # Database connection
├── style.css          # CSS styling
├── database_setup.sql # Database setup script
└── README.md          # This file
```

## Security Notes

- Passwords are hashed using MD5 (consider using more secure hashing for production)
- Basic input validation is implemented
- Session-based authentication is used
- SQL injection protection should be enhanced for production use

## Browser Compatibility

- Works on all modern browsers
- Responsive design for mobile devices
- Requires JavaScript enabled for form switching
