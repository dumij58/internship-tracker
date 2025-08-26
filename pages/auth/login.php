<?php
// Admin dashboard page for managing users and functionalities.
require_once '../../includes/config.php';
//requireAdmin();

// --- Page-specific variables ---
$page_title = 'Login';
global $pages_path;
$db = getDB();

// --- Include the header ---
require_once '../../includes/header.php';
?>

<div class="login-container">
    <div class="form-box">
        <h1 id="title">Sign Up</h1>
        <form method="POST" action="register.php" id="loginForm">
            <div class="input-group">
                <div class="input-field" id="namefield">
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="input-field">
                    <input type="email" name= "email" placeholder="Email" required>
                </div>

                <div class="input-field">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <p class="lost-pass">Lost password? <a href="#">Click here</a></p>
            </div>
            <div class="btn-field">
                <button type="submit" id="signupBtn" name="signUp">Sign up</button>
                <button type="button" class="disable" id="signinBtn">Sign in</button>
            </div>
        </form>
    </div>
</div>

<?php
// --- Include the footer ---
require_once '../../includes/footer.php';
?>