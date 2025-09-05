<?php
require_once '../../includes/config.php';
$db = getDB();

if (isset($_POST['signUp'])){
    $username = $_POST['name']; // Using name from form as username
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type_id = $_POST['user_type']; // Get user type from form
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Validate user type
    if (!in_array($user_type_id, ['2', '3'])) {
        echo "<script>alert('Please select a valid account type'); window.location.href='login.php';</script>";
        exit;
    }

    // Check if email already exists using prepared statement
    $checkEmail = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($checkEmail);
    $stmt->execute([$email]);
    
    if($stmt->rowCount() > 0){
        echo "<script>alert('Email Address Already exists'); window.location.href='login.php';</script>";
    }
    else{
        // Insert new user using prepared statement with selected user type
        $insertQuery = "INSERT INTO users(username, email, password_hash, user_type_id) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($insertQuery);
        
        try {
            $stmt->execute([$username, $email, $password_hash, $user_type_id]);
            $user_type_name = ($user_type_id == '2') ? 'Student' : 'Company';
            echo "<script>alert('Thank you " . $username . "! " . $user_type_name . " registration successful.'); window.location.href='login.php';</script>";
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
            $_SESSION['role'] = getUserRole($row['user_type_id']);

            // Check if user has completed profile based on user type
            if ($row['user_type_id'] == 2) {
                // Student - check student_profiles table
                $check_profile = "SELECT user_id FROM student_profiles WHERE user_id = ?";
                $stmt = $db->prepare($check_profile);
                $stmt->execute([$row['user_id']]);

                if($stmt->rowCount() > 0) {
                    // Profile exists, redirect to main page
                    echo "<script>
                        alert('Welcome back, " . $row['username'] . "!');
                        window.location.href='../../index.php';
                    </script>";
                } else {
                    // No profile, redirect to student details form
                    echo "<script>
                        alert('Welcome " . $row['username'] . "! Please enter your student details to complete your profile.');
                        window.location.href='user_details.php';
                    </script>";
                }
            } elseif ($row['user_type_id'] == 3) {
                // Company - check company_profiles table
                $check_profile = "SELECT user_id FROM company_profiles WHERE user_id = ?";
                $stmt = $db->prepare($check_profile);
                $stmt->execute([$row['user_id']]);

                if($stmt->rowCount() > 0) {
                    // Profile exists, redirect to main page
                    echo "<script>
                        alert('Welcome back, " . $row['username'] . "!');
                        window.location.href='../../index.php';
                    </script>";
                } else {
                    // No profile, redirect to company details form
                    echo "<script>
                        alert('Welcome " . $row['username'] . "! Please enter your company details to complete your profile.');
                        window.location.href='company_details.php';
                    </script>";
                }
            } else {
                // Admin or other user types - redirect to main page
                echo "<script>
                    alert('Welcome back, " . $row['username'] . "!');
                    window.location.href='../../index.php';
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