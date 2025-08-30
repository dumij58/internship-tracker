<?php
require_once '../includes/config.php';
$page_title = 'Error - ' . escape($error_code);
require_once '../includes/header.php';
?>

<h1><?php echo 'Error ' . escape($error_code); ?></h1>
<p><?php echo escape($error_message); ?></p>
<br>
<?php
    echo '<a href="/internship_tracker/login.php" class="button">Return to Login</a>';
?>

<?php
require_once '../includes/footer.php';
?>
