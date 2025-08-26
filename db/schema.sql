-- Create Database
CREATE DATABASE IF NOT EXISTS internship_tracker;
USE internship_tracker;

-- User Types/Roles Table
CREATE TABLE user_types (
    type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    type_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table (Main user entity)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    user_type_id INT NOT NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_type_id) REFERENCES user_types(type_id)
);

-- Student Profiles (Extended info for students)
CREATE TABLE student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    student_id VARCHAR(20),
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    university VARCHAR(100),
    major VARCHAR(100),
    year_of_study INT,
    gpa DECIMAL(3,2),
    resume_path VARCHAR(255),
    portfolio_url VARCHAR(255),
    bio TEXT,
    skills TEXT, -- JSON or comma-separated
    languages TEXT, -- JSON or comma-separated
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Company Profiles (For company representatives)
CREATE TABLE company_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    company_name VARCHAR(100) NOT NULL,
    company_website VARCHAR(255),
    company_description TEXT,
    address TEXT,
    verified BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Internship Categories
CREATE TABLE internship_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    category_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Main Internships Table
CREATE TABLE internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    company_id INT NOT NULL, -- Links to company_profiles
    category_id INT NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    responsibilities TEXT,
    location VARCHAR(100),
    duration_months INT,
    stipend DECIMAL(10,2),
    application_deadline DATE,
    start_date DATE,
    end_date DATE,
    max_applicants INT DEFAULT 50,
    status ENUM('draft', 'published', 'closed', 'cancelled') DEFAULT 'draft',
    remote_option BOOLEAN DEFAULT FALSE,
    experience_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES company_profiles(profile_id),
    FOREIGN KEY (category_id) REFERENCES internship_categories(category_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id)
);

-- Internship Skills Required
CREATE TABLE internship_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    skill_level ENUM('basic', 'intermediate', 'advanced') DEFAULT 'basic',
    is_required BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (internship_id) REFERENCES internships(internship_id) ON DELETE CASCADE
);

-- Applications Table
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    student_id INT NOT NULL, -- Links to users table
    resume_path VARCHAR(255),
    additional_documents TEXT, -- JSON array of file paths
    status ENUM('submitted', 'under_review', 'shortlisted', 'rejected', 'accepted', 'withdrawn') DEFAULT 'submitted',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_date TIMESTAMP NULL,
    reviewed_by INT NULL,
    interview_scheduled TIMESTAMP NULL,
    interview_notes TEXT,
    FOREIGN KEY (internship_id) REFERENCES internships(internship_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id),
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    UNIQUE KEY unique_application (internship_id, student_id)
);

-- System Logs (for audit trail)
CREATE TABLE system_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    details VARCHAR(255),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Notifications Table
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);



-- ====================================
--  INSERT DEFAULT DATA
-- ====================================

-- Insert User Types
INSERT INTO user_types (type_name, type_description) VALUES
('administrator', 'System administrator with full access'),
('student', 'Student user who can apply for internships'),
('company', 'Company representative who can post internships'),

-- Insert Default Categories
INSERT INTO internship_categories (category_name, category_description) VALUES
('Software Development', 'Programming and software engineering roles'),
('Data Science', 'Data analysis, machine learning, and analytics'),
('Marketing', 'Digital marketing, content creation, and brand management'),
('Design', 'UI/UX design, graphic design, and creative roles'),
('Business', 'Business development, consulting, and management'),
('Research', 'Academic and industry research positions');

-- Create Default Admin User (password: admin123)
INSERT INTO users (username, email, password_hash, first_name, last_name, user_type_id) VALUES
('admin', 'admin@internship-system.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', 1);

-- Create Default Student User (password: student123)
INSERT INTO users (username, email, password_hash, first_name, last_name, user_type_id) VALUES
('student', 'student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Student', '1', 2);
('student2', 'student2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Student', '2', 2);

-- Create Student Profile for Default Student
INSERT INTO student_profiles (user_id, student_id, first_name, last_name, phone, bio, university, major, year_of_study, gpa) VALUES
(2, 'STU001', 'Student1', 'Name', '94712808865', 'Default user for InternSphere web application', 'Default University', 'Computer Science', 3, 3.50);