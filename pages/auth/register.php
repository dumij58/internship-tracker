<?php
require_once '../../includes/config.php';
$db = getDB();

if (isset($_POST['signUp'])){
    $username = $_POST['name']; // Using name from form as username
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists using prepared statement
    $checkEmail = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($checkEmail);
    $stmt->execute([$email]);
    
    if($stmt->rowCount() > 0){
        echo "<script>alert('Email Address Already exists'); window.location.href='login.php';</script>";
    }
    else{
        // Insert new user using prepared statement (assuming user_type_id = 2 for students)
        $insertQuery = "INSERT INTO users(username, email, password_hash, user_type_id) VALUES (?, ?, ?, 2)";
        $stmt = $db->prepare($insertQuery);
        
        try {
            $stmt->execute([$username, $email, $password_hash]);
            echo "<script>alert('Thank you " . $username . "! Registration successful.'); window.location.href='login.php';</script>";
        } catch(PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='login.php';</script>";
        }
    }       
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Login using prepared statement
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);
    
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch();
        // Verify password
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type_id'] = $row['user_type_id'];
            
            // Check if user has completed profile
            $check_profile = "SELECT id FROM student_profiles WHERE user_id = ?";
            $stmt = $db->prepare($check_profile);
            $stmt->execute([$row['user_id']]);
            
            if($stmt->rowCount() > 0) {
                // Profile exists, redirect to main page
                echo "<script>
                    alert('Welcome back, " . $row['username'] . "!');
                    window.location.href='../../pages/index.php';
                </script>";
            } else {
                // No profile, redirect to user details form
                echo "<script>
                    alert('Welcome " . $row['username'] . "! Please enter your user details to complete your profile.');
                    window.location.href='user_details.php';
                </script>";
            }
        } else {
            echo "<script>alert('Incorrect email or password'); window.location.href='login.php';</script>";
        }
    }
    else {
        echo "<script>alert('Incorrect email or password'); window.location.href='login.php';</script>";
    }
}
?>