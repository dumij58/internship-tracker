<?php
// Quick password change widget - can be included in user profiles or dashboards
// This file should be included in other pages, not accessed directly

if (!isLoggedIn()) {
    return; // Don't show if user is not logged in
}
?>

<div class="password-widget">
    <div class="widget-header">
        <h3>🔒 Password & Security</h3>
    </div>
    
    <div class="widget-content">
        <p>Keep your account secure by regularly updating your password.</p>
        
        <div class="widget-actions">
            <a href="<?php echo $pages_path; ?>/auth/change_password.php" class="btn btn-primary">
                Change Password
            </a>
            
            <div class="security-info">
                <small>
                    <strong>Last Password Update:</strong> 
                    <?php
                    $last_update_sql = "SELECT updated_at FROM users WHERE user_id = ?";
                    $last_update_stmt = getDB()->prepare($last_update_sql);
                    $last_update_stmt->execute([getCurrentUserId()]);
                    $last_update = $last_update_stmt->fetchColumn();
                    echo $last_update ? date('M j, Y', strtotime($last_update)) : 'Unknown';
                    ?>
                </small>
            </div>
        </div>
        
        <div class="security-tips">
            <details>
                <summary>Password Security Tips</summary>
                <ul>
                    <li>Use at least 8 characters</li>
                    <li>Include uppercase and lowercase letters</li>
                    <li>Add numbers and special characters</li>
                    <li>Don't reuse passwords from other sites</li>
                    <li>Change your password every 3-6 months</li>
                </ul>
            </details>
        </div>
    </div>
</div>

<style>
.password-widget {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.widget-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.widget-header h3 {
    margin: 0;
    color: #333;
    font-size: 16px;
}

.widget-content {
    padding: 20px;
}

.widget-content p {
    margin: 0 0 15px 0;
    color: #666;
}

.widget-actions {
    margin-bottom: 15px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    text-decoration: none;
}

.security-info {
    margin-top: 10px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 4px;
}

.security-info small {
    color: #6c757d;
}

.security-tips {
    margin-top: 15px;
    border-top: 1px solid #e0e0e0;
    padding-top: 15px;
}

.security-tips details {
    cursor: pointer;
}

.security-tips summary {
    font-weight: 500;
    color: #007bff;
    margin-bottom: 10px;
}

.security-tips ul {
    margin: 0;
    padding-left: 20px;
}

.security-tips li {
    margin-bottom: 5px;
    color: #666;
    font-size: 14px;
}
</style>
