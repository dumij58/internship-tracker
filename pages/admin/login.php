<?php
require_once '../../includes/config.php';

// --- Page-specific variables ---
$page_title = 'Admin Login';
global $pages_path;
$db = getDB();

if (isLoggedIn() && isAdmin()) {
    header('Location: '. $pages_path . '/admin/index.php');
    exit();
}

require_once '../../includes/header.php';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = escape($_POST['username']);
    $password = $_POST['password']; // Don't escape password as it might contain special characters
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        
        // Prepare statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :uname");
        $stmt->execute(['uname' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = getUserRole($user['user_type_id']);
                
                // Update last login time
                $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE user_id = :uid");
                $updateStmt->execute(['uid' => $user['user_id']]);
                
                // Log successful login
                logActivity('Login Success', "User: {$user['username']}");
                
                // Redirect to original page or home
                $redirect = $_SESSION['redirect_after_login'] ?? $pages_path . '/admin/index.php';
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit();
            } else {
                $error = 'Invalid username or password.';
                logActivity('Login Failed - Wrong Password', "Username: $username");
            }
        } else {
            $error = 'Invalid username or password.';
            logActivity('Login Failed - User Not Found', "Username: $username");
        }
    }
}
?>

<div class="login-container">
    <div class="form-box">
        <h1>Admin Login</h1>
        <form action="login.php" method="POST">
            <div class="input-group">
                <div class="input-field">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-field">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <div class="error-message">
                <?php if (isset($error)): ?>
                    <p><?php echo escape($error); ?></p>
                <?php endif; ?>
            </div>
            <div class="btn-field-center">
                <button class="login-btn" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>

<?php
// --- Include the footer ---
require_once '../../includes/footer.php';
?>