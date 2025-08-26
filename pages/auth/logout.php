<?php
// Destroys any user's session and logs them out.

require_once '../../includes/config.php';

// Unset all of the session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session.
session_destroy();

// Redirect to the main login page
header("Location: login.php");
exit();
?>