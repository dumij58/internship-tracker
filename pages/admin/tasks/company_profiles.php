<?php
// Admin dashboard page for managing users and functionalities.
require_once '../../../includes/config.php';
//requireAdmin();

// --- Page-specific variables ---
$page_title = 'Manage Company Profiles';
$db = getDB();

// --- Include the header ---
require_once '../../../includes/header_admin.php';
?>

<div class="admin-panel-tasks">
    <h2>Company Profiles</h2>
    <table border="1" colspan="4" width="90%" align="centers">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php
        // Fetch users from the database
        $stmt = $db->query("SELECT user_id, username, email, user_type_id FROM users WHERE user_type_id = 3");
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role = getUserRole($user['user_type_id']);
            echo "<tr align='center'>
                    <td>{$user['user_id']}</td>
                    <td>{$user['username']}</td>
                    <td>{$user['email']}</td>
                </tr>";
        }
        ?>
    </table>
</div>

<?php
// --- Include the footer ---
require_once '../../../includes/footer.php';
?>