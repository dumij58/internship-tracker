<?php
// Admin dashboard page for managing users and functionalities.
require_once '../../../includes/config.php';
//requireAdmin();

// --- Page-specific variables ---
$page_title = 'Manage Applications';
$db = getDB();

// --- Include the header ---
require_once '../../../includes/header_admin.php';
?>

<div class="admin-panel-tasks">
    <h2>Applications</h2>
    <table border="1" colspan="4" width="90%" align="center">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Internship ID</th>
            <th>Status</th>
        </tr>
        <?php
        // Fetch applications from the database
        $stmt = $db->query("SELECT application_id, student_id, internship_id, status FROM applications");
        while ($application = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $student = getUser($application['student_id']);
            // $company = getUser($application['company_id']);
            echo "<tr align='center'>
                    <td>{$application['application_id']}</td>
                    <td>{$student['username']}</td>
                    <td>{'<<< ToDo >>>'}</td>
                    <td>{$application['status']}</td>
                  </tr>";
        }
        ?>
    </table>
</div>

<?php
// --- Include the footer ---
require_once '../../../includes/footer.php';
?>