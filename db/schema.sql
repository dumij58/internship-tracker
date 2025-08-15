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
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    user_type_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_type_id) REFERENCES user_types(type_id)
);

-- Student Profiles (Extended info for students)
CREATE TABLE student_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    student_id VARCHAR(20),
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
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    company_name VARCHAR(100) NOT NULL,
    company_website VARCHAR(255),
    industry VARCHAR(100),
    company_size ENUM('startup', 'small', 'medium', 'large', 'enterprise'),
    company_description TEXT,
    address TEXT,
    verified BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Internship Categories
CREATE TABLE internship_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    category_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Main Internships Table
CREATE TABLE internships (
    internship_id INT AUTO_INCREMENT PRIMARY KEY,
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
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    skill_level ENUM('basic', 'intermediate', 'advanced') DEFAULT 'basic',
    is_required BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (internship_id) REFERENCES internships(internship_id) ON DELETE CASCADE
);

-- Applications Table
CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT NOT NULL,
    student_id INT NOT NULL, -- Links to users table
    cover_letter TEXT,
    resume_path VARCHAR(255),
    additional_documents TEXT, -- JSON array of file paths
    status ENUM('submitted', 'under_review', 'shortlisted', 'rejected', 'accepted', 'withdrawn') DEFAULT 'submitted',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_date TIMESTAMP NULL,
    reviewed_by INT NULL,
    feedback TEXT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    interview_scheduled TIMESTAMP NULL,
    interview_notes TEXT,
    FOREIGN KEY (internship_id) REFERENCES internships(internship_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id),
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id),
    UNIQUE KEY unique_application (internship_id, student_id)
);

-- Application Status History (Track status changes)
CREATE TABLE application_status_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by INT NOT NULL,
    change_reason TEXT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(application_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(user_id)
);

-- User Sessions (for session management)
CREATE TABLE user_sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- System Logs (for audit trail)
CREATE TABLE system_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_affected VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- System Settings
CREATE TABLE system_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_description TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(user_id)
);

-- Notifications Table
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    related_table VARCHAR(100),
    related_id INT,
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
('moderator', 'Moderator with limited admin privileges');

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
('student', 'student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Default', 'Student', 2);

-- Create Student Profile for Default Student
INSERT INTO student_profiles (user_id, student_id, university, major, year_of_study, gpa) VALUES
(2, 'STU001', 'Default University', 'Computer Science', 3, 3.50);

-- Insert Default System Settings
INSERT INTO system_settings (setting_key, setting_value, setting_description) VALUES
('site_name', 'Internship Application & Tracking System', 'Name of the application'),
('max_applications_per_student', '10', 'Maximum applications a student can submit'),
('application_deadline_buffer', '7', 'Days before deadline when applications close'),
('email_notifications', '1', 'Enable email notifications (1=yes, 0=no)'),
('file_upload_max_size', '5242880', 'Maximum file upload size in bytes (5MB)');