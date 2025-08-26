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

            <?php else: ?>

            <!-- Public Navigation -->
            <a href="<?php echo $pages_path; ?>/index.php" class="nav-brand">Internship Tracker</a>
            <div class="nav-links">

            <?php endif; ?>

                <a href="<?php echo $pages_path; ?>/functionalities.php">Functionalities</a>
                <a href="<?php echo $pages_path; ?>/help.php">Help</a>
                <div class="nav-user dropdown">

                    <?php if (isset($_SESSION['role'])): ?>

                    <span class="nav-user-name">
                        <?php echo $_SESSION['username'] ?>
                    </span>
                    <span class="nav-user-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                    </span>
                    <div class="dropdown-content">
                        <a href="<?php echo $pages_path;?>/auth/logout.php">Logout</a>
                    </div>

                    <?php else: ?>

                    <span class="nav-login">
                        <a href="<?php echo $pages_path . '/auth/login.php' ?>">Login</a>
                    </span>
                    <span class="nav-register">
                        <a href="<?php echo $pages_path . '/auth/register.php' ?>">Register</a>
                    </span>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="content">
