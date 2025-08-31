<?php
// A reusable header and navigation bar for the entire application.

require_once 'config.php';

// Set a default title if one isn't provided by the page including this file
$current_page_title = isset($page_title) ? $page_title : 'InternSphere';
$root_path = isset($path_prefix) ? $path_prefix : '/internship-tracker';
$pages_path = $root_path . '/pages';
$assets_path = $root_path . '/assets';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($current_page_title); ?> - InternSphere/title>
    <link rel="stylesheet" href="<?php echo $assets_path; ?>/css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <nav class="nav-bar">
                <div class="nav-brand">
                    <a class="nav-brand-link" href="<?php echo $pages_path; ?>/admin/index.php">
                        <img src="<?php echo $assets_path; ?>/images/logo.webp" class="nav-brand-logo" alt="InternSphere Logo">
                        <span class="nav-brand-name">Admin Dashboard - InternSphere</span>
                    </a>
                </div>
                <div class="nav-links-container">
                    <div class="nav-links">
                        <?php if(isLoggedIn() && isAdmin()):?>
                        <a href="<?php echo $pages_path; ?>/admin/tasks.php">Tasks</a>
                        <a href="<?php echo $pages_path; ?>/admin/notifications.php">Notifications</a>
                        <a href="<?php echo $pages_path; ?>/admin/reports.php">Reports</a>
                        <a href="<?php echo $pages_path; ?>/admin/feedback.php">Feedback</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <div class="content">
