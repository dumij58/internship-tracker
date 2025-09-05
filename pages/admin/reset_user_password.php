<?php
require_once '../../includes/config.php';

// Require admin access
requireAdmin();

$db = getDB();
$message = '';
$message_type = '';
$users = [];

// Get all users for the dropdown
$users_sql = "SELECT u.user_id, u.username, u.email, ut.type_name 
              FROM users u 
              JOIN user_types ut ON u.user_type_id = ut.type_id 
              ORDER BY u.username";
$users_stmt = $db->prepare($users_sql);
$users_stmt->execute();
$users = $users_stmt->fetchAll();

// Handle admin password reset
if (isset($_POST['admin_reset_password'])) {
    $target_user_id = $_POST['target_user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($target_user_id) || empty($new_password) || empty($confirm_password)) {
        $message = 'Please fill in all fields.';
        $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = 'Passwords do not match.';
        $message_type = 'error';
    } else {
        // Get target user info
        $target_sql = "SELECT username, email FROM users WHERE user_id = ?";
        $target_stmt = $db->prepare($target_sql);
        $target_stmt->execute([$target_user_id]);
        $target_user = $target_stmt->fetch();
        
        if ($target_user) {
            // Update password
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE user_id = ?";
            $update_stmt = $db->prepare($update_sql);
            
            try {
                $update_stmt->execute([$password_hash, $target_user_id]);
                
                // Log the admin password reset
                logActivity('Admin Password Reset', "Admin reset password for user: {$target_user['username']} ({$target_user['email']})");
                
                $message = "Password successfully reset for user: {$target_user['username']} ({$target_user['email']})";
                $message_type = 'success';
                
                // Clear form
                $_POST = array();
                
            } catch (PDOException $e) {
                $message = 'An error occurred while resetting the password. Please try again.';
                $message_type = 'error';
                logActivity('Admin Password Reset Error', "Error resetting password for user {$target_user['username']}: " . $e->getMessage());
            }
        } else {
            $message = 'User not found.';
            $message_type = 'error';
        }
    }
}

// Page variables
$page_title = 'Admin - Reset User Password';
global $pages_path, $assets_path;

// Include header
require_once '../../includes/header.php';
?>

<div class="admin-container">
    <?php require_once '../../includes/admin_back_to_dash.php'; ?>
    
    <div class="admin-content">
        <div class="form-box">
            <h1>Reset User Password</h1>
            <p class="warning-text">⚠️ Use this feature carefully. The user will need to use the new password to log in.</p>
            
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="adminResetForm">
                <div class="input-group">
                    <div class="input-field">
                        <label for="target_user_id">Select User</label>
                        <select id="target_user_id" name="target_user_id" required>
                            <option value="">-- Select a user --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['user_id']; ?>" 
                                        <?php echo (isset($_POST['target_user_id']) && $_POST['target_user_id'] == $user['user_id']) ? 'selected' : ''; ?>>
                                    <?php echo escape($user['username']); ?> (<?php echo escape($user['email']); ?>) - <?php echo escape($user['type_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                    <button type="submit" name="admin_reset_password" class="btn-primary" 
                            onclick="return confirm('Are you sure you want to reset this user\'s password?')">
                        Reset Password
                    </button>
                    <button type="button" class="btn-secondary" onclick="clearForm()">Clear Form</button>
                </div>
            </form>
            
            <div class="user-list">
                <h3>All Users</h3>
                <div class="users-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Last Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $users_with_login_sql = "SELECT u.username, u.email, ut.type_name, u.last_login 
                                                   FROM users u 
                                                   JOIN user_types ut ON u.user_type_id = ut.type_id 
                                                   ORDER BY u.username";
                            $users_with_login_stmt = $db->prepare($users_with_login_sql);
                            $users_with_login_stmt->execute();
                            $users_with_login = $users_with_login_stmt->fetchAll();
                            
                            foreach ($users_with_login as $user): 
                            ?>
                                <tr>
                                    <td><?php echo escape($user['username']); ?></td>
                                    <td><?php echo escape($user['email']); ?></td>
                                    <td><?php echo escape($user['type_name']); ?></td>
                                    <td><?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.admin-content {
    margin-top: 20px;
}

.form-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.form-box h1 {
    margin-bottom: 20px;
    color: #333;
}

.warning-text {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 25px;
    font-weight: 500;
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

.input-field input, .input-field select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.input-field input:focus, .input-field select:focus {
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
    background: #dc3545;
    color: white;
}

.btn-primary:hover {
    background: #c82333;
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

.user-list {
    margin-top: 40px;
}

.user-list h3 {
    margin-bottom: 15px;
    color: #333;
}

.users-table {
    overflow-x: auto;
}

.users-table table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.users-table th,
.users-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.users-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.users-table tr:hover {
    background-color: #f8f9fa;
}
</style>

<script>
function clearForm() {
    document.getElementById('adminResetForm').reset();
}

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
