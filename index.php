<?php
require_once 'includes/config.php';

// --- Page-specific variables ---
$page_title = 'Home';

if (isLoggedIn()) {
    if (isAdmin()) {
        // Redirect to admin dashboard
        header('Location: pages/admin/index.php');
        exit;
    } else {
        // Redirect to user dashboard
        $role = $_SESSION['role'];
        header("Location: pages/$role/index.php");
        exit;
    }
}

// --- Include the header ---
require_once 'includes/header.php';
?>

<h1>Welcome, <?php echo isset($_SESSION['username']) ? escape($_SESSION['username']) : 'Guest'; ?>!</h1>

<?php
// --- Include the footer ---
require_once 'includes/footer.php';
?>