<?php
require_once '../../includes/config.php';

// Check if user is already logged in
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    header("Location: ../$role/index.php");
    exit;
}

$db = getDB();
$message = '';
$message_type = '';
$valid_token = false;
$user_data = null;

// Check if token and email are provided
if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    
    // Validate token from system_logs
    $sql = "SELECT sl.*, u.user_id, u.username, u.email 
            FROM system_logs sl 
            JOIN users u ON sl.user_id = u.user_id 
            WHERE sl.action = 'PASSWORD_RESET_REQUEST' 
            AND u.email = ? 
            AND sl.created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ORDER BY sl.created_at DESC 
            LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $log_entry = $stmt->fetch();
        $details = json_decode($log_entry['details'], true);
        
        if (isset($details['reset_token']) && $details['reset_token'] === $token) {
            $valid_token = true;
            $user_data = $log_entry;
        }
    }
    
    if (!$valid_token) {
        $message = 'Invalid or expired reset token. Please request a new password reset.';
        $message_type = 'error';
    }
} else {
    $message = 'Invalid reset link. Please request a new password reset.';
    $message_type = 'error';
}

// Handle password reset form submission
if (isset($_POST['reset_password']) && $valid_token) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($new_password) || empty($confirm_password)) {
        $message = 'Please fill in all fields.';
        $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = 'Passwords do not match.';
        $message_type = 'error';
    } else {
        // Update password
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE email = ?";
        $update_stmt = $db->prepare($update_sql);
        
        try {
            $update_stmt->execute([$password_hash, $email]);
            
            // Log the password change
            logActivity('Password Reset Completed', "Password reset completed for email: $email");
            
            // Mark the reset token as used by adding a completion log
            $completion_sql = "INSERT INTO system_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())";
            $completion_stmt = $db->prepare($completion_sql);
            $completion_details = json_encode(['email' => $email, 'reset_token_used' => $token]);
            $completion_stmt->execute([$user_data['user_id'], 'PASSWORD_RESET_COMPLETED', $completion_details]);
            
            $message = 'Password reset successful! You can now log in with your new password.';
            $message_type = 'success';
            $valid_token = false; // Prevent further use of the form
            
        } catch (PDOException $e) {
            $message = 'An error occurred while resetting your password. Please try again.';
            $message_type = 'error';
            logActivity('Password Reset Error', "Error resetting password for $email: " . $e->getMessage());
        }
    }
}

// Page variables
$page_title = 'Reset Password';
global $pages_path, $assets_path;

// Include header
require_once '../../includes/header.php';
?>

<div class="login-container">
    <div class="form-box">
        <h1>Reset Your Password</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($valid_token): ?>
            <p class="user-info">Resetting password for: <strong><?php echo escape($user_data['email']); ?></strong></p>
            
            <form method="POST" action="">
                <input type="hidden" name="token" value="<?php echo escape($token); ?>">
                <input type="hidden" name="email" value="<?php echo escape($email); ?>">
                
                <div class="input-group">
                    <div class="input-field">
                        <input type="password" name="new_password" placeholder="New Password" required minlength="6">
                        <small>Password must be at least 6 characters long</small>
                    </div>
                    
                    <div class="input-field">
                        <input type="password" name="confirm_password" placeholder="Confirm New Password" required minlength="6">
                    </div>
                </div>
                
                <div class="btn-field">
                    <button type="submit" name="reset_password" class="btn-primary">Reset Password</button>
                </div>
            </form>
        <?php endif; ?>
        
        <div class="form-links">
            <p><a href="login.php">← Back to Login</a></p>
            <?php if (!$valid_token): ?>
                <p><a href="reset_password.php">Request New Reset Link</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    padding: 20px;
}

.form-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.form-box h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.user-info {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
    color: #495057;
}

.input-group {
    margin-bottom: 20px;
}

.input-field {
    margin-bottom: 15px;
}

.input-field input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

.input-field input:focus {
    outline: none;
    border-color: #007bff;
}

.input-field small {
    color: #6c757d;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

.btn-field {
    margin-bottom: 20px;
}

.btn-primary {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background: #0056b3;
}

.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.message.info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.form-links {
    text-align: center;
    margin-top: 20px;
}

.form-links p {
    margin: 10px 0;
}

.form-links a {
    color: #007bff;
    text-decoration: none;
}

.form-links a:hover {
    text-decoration: underline;
}
</style>

<?php
// Include footer
require_once '../../includes/footer.php';
?>
