<?php
require_once '../includes/config.php';
// requireLogin();  // uncomment in non-development environments

// --- Page-specific variables ---
$page_title = 'Home';

// --- Include the header ---
require_once '../includes/header.php';
?>

<p style='color:red;'> The content for this page starts here </p>
<br />
<br />

<h1>Welcome, <?php echo escape($_SESSION['username']); ?>!</h1>
<p>This is your dashboard.</p>

<br />
<br />
<p style='color:red;'> The content for this page ends here </p>

<?php
// --- Include the footer ---
require_once '../includes/footer.php';
?>
