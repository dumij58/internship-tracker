<?php
/**
 * This file contains all the essential settings and functions used throughout the application
 * It should be included at the beginning of every PHP file that needs database access or session management
 */

//ob_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // Only for development purposes
        //$_SESSION['username'] = 'student';
        //$_SESSION['role'] = 'student';
        $_SESSION['username'] = 'company';
        $_SESSION['role'] = 'company';
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'internship_tracker');
define('DB_USER', 'dumij58'); // Change this to your MySQL username
define('DB_PASS', 'mysqldbpassword'); // Change this to your MySQL password

// Application Settings
define('SITE_NAME', 'Internship Application & Tracking System');
define('SITE_URL', 'http://localhost/internship_tracker/'); // Update this to your actual URL
// define('UPLOAD_DIR', __DIR__ . '/uploads/'); // Directory for file uploads
// define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB max file size

// Security Settings
define('HASH_ALGO', PASSWORD_DEFAULT);

// Time zone setting
date_default_timezone_set('Asia/Colombo'); // Adjust to your timezone

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Determine the root path for correct asset and link paths
// This allows the header to be used from any directory level
define('ROOT_PATH', isset($path_prefix) ? $path_prefix : '/internship-tracker');
define('PAGES_PATH', ROOT_PATH . '/pages');

/**
 * Database Connection Function
 * Creates and returns a PDO connection object
 * Throws an exception if connection fails
 */
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;

    public function getDBConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                 $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Global database instance
function getDB() {
    $db = new Database();
    return $db->getDBConnection();
}

// Fetches a user's record from the database by their username.
function getUser($username) {
    $db = getDB();
    try {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        // In a real application, you would log this error.
        error_log("Database error in getUser: " . $e->getMessage());
        return false;
    }
}

// Get current user ID
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Get current user type
function getCurrentUserType() {
    return isset($_SESSION['user_type_id']) ? $_SESSION['user_type_id'] : null;
}

// Redirect to login if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . PAGES_PATH . '/login.php?msg=Please login to continue');
        exit();
    }
}

// Redirects to home if user is not an admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . PAGES_PATH . '/index.php?msg=Access denied. Admin privileges required.');
        exit();
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if current user is admin
function isAdmin() {
    return getCurrentUserType() === 'administrator';
}

// Hash password securely
function hashPassword($password) {
    return password_hash($password, HASH_ALGO);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Sanitizes output to prevent XSS attacks.
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

?>