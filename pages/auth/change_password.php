<?php
require_once '../../includes/config.php';

// Require user to be logged in
requireLogin();

$db = getDB();
$message = '';
$message_type = '';
$user_id = getCurrentUserId();

// Handle password change form submission
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = 'Please fill in all fields.';
        $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
        $message = 'New password must be at least 6 characters long.';
        $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = 'New passwords do not match.';
        $message_type = 'error';
    } else {
        // Verify current password
        $sql = "SELECT password_hash FROM users WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            
            if (password_verify($current_password, $user['password_hash'])) {
                // Current password is correct, update to new password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE user_id = ?";
                $update_stmt = $db->prepare($update_sql);
                
                try {
                    $update_stmt->execute([$new_password_hash, $user_id]);
                    
                    // Log the password change
                    logActivity('Password Changed', 'User changed their password successfully');
                    
                    $message = 'Password changed successfully!';
                    $message_type = 'success';
                    
                    // Clear form fields after successful change
                    $_POST = array();
                    
                } catch (PDOException $e) {
                    $message = 'An error occurred while changing your password. Please try again.';
                    $message_type = 'error';
                    logActivity('Password Change Error', 'Error changing password: ' . $e->getMessage());
                }
            } else {
                $message = 'Current password is incorrect.';
                $message_type = 'error';
            }
        } else {
            $message = 'User not found.';
            $message_type = 'error';
        }
    }
}

// Get user info for display
$user_sql = "SELECT username, email FROM users WHERE user_id = ?";
$user_stmt = $db->prepare($user_sql);
$user_stmt->execute([$user_id]);
$user_info = $user_stmt->fetch();

// Page variables
$page_title = 'Change Password';
global $pages_path, $assets_path;

// Include header
require_once '../../includes/header.php';
?>

<div class="change-password-container">
    <div class="form-box">
        <h1>Change Password</h1>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo escape($user_info['username']); ?></p>
            <p><strong>Email:</strong> <?php echo escape($user_info['email']); ?></p>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="changePasswordForm">
            <div class="input-group">
                <div class="input-field">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="input-field">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6">
                    <small>Password must be at least 6 characters long</small>
                </div>
                
                <div class="input-field">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
            </div>
            
            <div class="btn-field">
                <button type="submit" name="change_password" class="btn-primary">Change Password</button>
                <button type="button" class="btn-secondary" onclick="clearForm()">Clear Form</button>
            </div>
        </form>
        
        <div class="form-links">
            <p><a href="../<?php echo $_SESSION['role']; ?>/index.php">← Back to Dashboard</a></p>
        </div>
    </div>
</div>

<style>
.change-password-container {
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
    max-width: 500px;
}

.form-box h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.user-info {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 25px;
    border-left: 4px solid #007bff;
}

.user-info p {
    margin: 5px 0;
    color: #495057;
}

.input-group {
    margin-bottom: 25px;
}

.input-field {
    margin-bottom: 20px;
}

.input-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.input-field input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.input-field input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.input-field small {
    color: #6c757d;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}

.btn-field {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
}

.btn-primary, .btn-secondary {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
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

/* Password strength indicator */
.password-strength {
    margin-top: 5px;
    height: 5px;
    border-radius: 3px;
    transition: all 0.3s;
}

.strength-weak { background-color: #dc3545; width: 33%; }
.strength-medium { background-color: #ffc107; width: 66%; }
.strength-strong { background-color: #28a745; width: 100%; }
</style>

<script>
function clearForm() {
    document.getElementById('changePasswordForm').reset();
}

// Password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^A-Za-z0-9]/)) strength++;
    
    // Remove existing strength indicator
    const existingIndicator = this.parentNode.querySelector('.password-strength');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Add new strength indicator
    if (password.length > 0) {
        const indicator = document.createElement('div');
        indicator.className = 'password-strength';
        
        if (strength <= 1) {
            indicator.classList.add('strength-weak');
        } else if (strength <= 2) {
            indicator.classList.add('strength-medium');
        } else {
            indicator.classList.add('strength-strong');
        }
        
        this.parentNode.appendChild(indicator);
    }
});

// Confirm password validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && newPassword !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php
// Include footer
require_once '../../includes/footer.php';
?>
