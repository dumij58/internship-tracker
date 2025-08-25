<?php
// A reusable header and navigation bar for the entire application.

require_once 'config.php';

// Set a default title if one isn't provided by the page including this file
$current_page_title = isset($page_title) ? $page_title : 'Internship Tracker';
$root_path = isset($path_prefix) ? $path_prefix : '/internship-tracker';
$pages_path = $root_path . '/pages';
$assets_path = $root_path . '/assets';
$includes_path = $root_path . '/includes';
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

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>

            <!-- Student Navigation -->
            <a href="<?php echo $pages_path; ?>/student/index.php" class="nav-brand">Internship Tracker</a>
            <div class="nav-links">
                <a href="<?php echo $pages_path; ?>/student/internships.php">Find Internships</a>
                <a href="<?php echo $pages_path; ?>/student/applications.php">My Applications</a>
                <a href="<?php echo $pages_path; ?>/student/profile.php">My Profile</a>

            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'company'): ?>

            <!-- Company Navigation -->
            <a href="<?php echo $pages_path; ?>/company/index.php" class="nav-brand">Internship Tracker</a>
            <div class="nav-links">
                <a href="<?php echo $pages_path; ?>/company/internships.php">Post Internships</a>
                <a href="<?php echo $pages_path; ?>/company/applications.php">Recieved Applications</a>
                <a href="<?php echo $pages_path; ?>/company/profile.php">Company Profile</a>

            <?php endif; ?>

                <a href="<?php echo $pages_path; ?>/functionalities.php">Functionalities</a>
                <a href="<?php echo $pages_path; ?>/help.php">Help</a>
                <div class="nav-user">
                    <span class="nav-uname">Username - <?php echo $_SESSION['username']?></span>
                    <a href="<?php echo $pages_path; ?>/auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="content">
