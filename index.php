<?php
require_once 'includes/config.php';

// --- Page-specific variables ---
$page_title = 'Home';

// --- Include the header ---
require_once 'includes/header.php';
?>

<h1>Welcome, <?php echo isset($_SESSION['username']) ? escape($_SESSION['username']) : 'Guest'; ?>!</h1>

<?php
// --- Include the footer ---
require_once 'includes/footer.php';
?>