<?php
// Admin dashboard page for managing users and functionalities.
require_once '../../includes/config.php';
requireAdmin();

// --- Page-specific variables ---
$page_title = 'Admin Login';
global $pages_path;
$tasks_path = $pages_path . '/admin/tasks';
$db = getDB();

// --- Include the header ---
require_once '../../includes/header.php';
?>

<div class="admin-panel">
    <div class="admin-analytics">
        <h2>System Analytics</h2>
        <table border="0" colspan="4" cellspacing="10" width="100%">
            <tr>
                <th>Users</th>
                <th>Applications</th>
                <th>Companies</th>
                <th>Internships</th>
            </tr>
            <tr align="center">
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
            </tr>
        </table>
    </div>
    <div class="admin-tasks">
        <h2>Administrative Tasks</h2>
        <table border="0" colspan="4" cellspacing="10" width="100%" class="admin-task-table">
            <tr>
                <th>Students</th>
                <th>Applications</th>
                <th>Companies</th>
                <th>Internships</th>
            </tr>
            <tr align="center" class="table-row-rg">
                <td><a href=<?php echo $tasks_path . '/students.php';?>>Manage Students</a></td>
                <td><a href=<?php echo $tasks_path . '/applications.php';?>>Manage Applications</a></td>
                <td><a href=<?php echo $tasks_path . '/companies.php';?>>Manage Companies</a></td>
                <td><a href=<?php echo $tasks_path . '/internships.php';?>>Manage Internships</a></td>
            </tr>
            <tr align="center" class="table-row-rg">
                <td><a href=<?php echo $tasks_path . '/student_profiles.php';?>>Manage Student Profiles</a></td>
                <td></td>
                <td><a href=<?php echo $tasks_path . '/company_profiles.php';?>>Manage Company Profiles</a></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="admin-logs">
        <div class="h2c">
            <span>Recent System Logs</span>
            <span class="h-link"><a href="<?php echo $tasks_path . '/system_logs.php'; ?>">View all logs</a></span>
        </div>
        <table border="0" colspan="4" cellspacing="10" width="100%">
            <tr>
                <th>User ID</th>
                <th>Action</th>
                <th>Details</th>
            </tr>
            <?php
            $stmt = $db->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 5");
            while ($log = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr align='center'>
                        <td>{$log['user_id']}</td>
                        <td>{$log['action']}</td>
                        <td>{$log['details']}</td>
                    </tr>";
            }
            ?>
        </table>
    </div>
</div>

<?php
// --- Include the footer ---
require_once '../../includes/footer.php';
?>