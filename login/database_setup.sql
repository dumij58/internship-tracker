-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS login;

-- Use the login database
USE login;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a test user (optional)
-- INSERT INTO users (name, email, password) VALUES ('Test User', 'test@example.com', MD5('password123'));
