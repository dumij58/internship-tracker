<?php
// Admin dashboard page for managing users and functionalities.
require_once '../../includes/config.php';
//requireAdmin();

// --- Page-specific variables ---
$page_title = 'Admin Login';
$db = getDB();

// --- Include the header ---
require_once '../../includes/header_admin.php';
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
            <tr align="center" rowspan="2">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="admin-tasks">
        <h2>Administrative Tasks</h2>
        <table border="0" colspan="4" cellspacing="10" width="100%">
            <tr>
                <th>Users</th>
                <th>Applications</th>
                <th>Companies</th>
                <th>Internships</th>
            </tr>
            <tr align="center">
                <td><a href="users_list.php">Manage Users</a></td>
                <td><a href="applications.php">Manage Applications</a></td>
                <td><a href="companies.php">Manage Companies</a></td>
                <td><a href="internships.php">Manage Internships</a></td>
            </tr>
            <tr align="center">
                <td><a href="user_profiles.php">Manage User Profiles</a></td>
                <td></td>
                <td><a href="company_profiles.php">Manage Company Profiles</a></td>
                <td><a href="internship_categories.php">Internship Categories</a></td>
            </tr>
        </table>
    </div>
</div>

<?php
// --- Include the footer ---
require_once '../../includes/footer.php';
?>