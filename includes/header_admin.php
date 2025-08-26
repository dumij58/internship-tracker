<?php
// A reusable header and navigation bar for the entire application.

require_once 'config.php';

// Set a default title if one isn't provided by the page including this file
$current_page_title = isset($page_title) ? $page_title : 'Internship Tracker';
$root_path = isset($path_prefix) ? $path_prefix : '/internship-tracker';
$pages_path = $root_path . '/pages';
$assets_path = $root_path . '/assets';
/*
if ($_SESSION['role'] === 'admin') {
    header('Location: '. $pages_path . '/admin/index.php');
    exit();
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($current_page_title); ?> - Internship Tracker</title>
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="nav-container">
            <a href="<?php echo $pages_path; ?>/admin/index.php" class="nav-brand">Admin Dashboard - Internship Tracker</a>
            <div class="nav-links">
                <?php if(isLoggedIn()):?>
                <a href="<?php echo $pages_path; ?>/admin/tasks.php">Tasks</a>
                <a href="<?php echo $pages_path; ?>/admin/notifications.php">Notifications</a>
                <a href="<?php echo $pages_path; ?>/admin/reports.php">Reports</a>
                <a href="<?php echo $pages_path; ?>/admin/feedback.php">Feedback</a>
                <div class="nav-user">
                    <span class="nav-uname"><?php $_SESSION['username'];?></span>
                    <a href="<?php echo $pages_path; ?>/auth/logout.php">Logout</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="content">
