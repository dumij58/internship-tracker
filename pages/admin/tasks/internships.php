<?php
// Admin dashboard page for managing users and functionalities.
require_once '../../../includes/config.php';
//requireAdmin();

// --- Page-specific variables ---
$page_title = 'Manage Applications';
$db = getDB();

// --- Include the header ---
require_once '../../../includes/header.php';
?>

<div class="admin-panel-tasks">
    <h2>Internships</h2>
    <table border="1" colspan="4" width="90%" align="center">
        <tr>
            <th>ID</th>
            <th>Company Username</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
        </tr>
        <?php
        // Fetch applications from the database
        $stmt = $db->query("SELECT internship_id, company_id, title, description, status FROM internships");
        while ($internship = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $company = getUser($internship['company_id']);
            echo "<tr align='center'>
                    <td>{$internship['internship_id']}</td>
                    <td>{$company['username']}</td>
                    <td>{$internship['title']}</td>
                    <td>{$internship['description']}</td>
                    <td>{$internship['status']}</td>
                  </tr>";
        }
        ?>
    </table>
</div>

<?php
// --- Include the footer ---
require_once '../../../includes/footer.php';
?>