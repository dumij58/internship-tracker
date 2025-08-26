<?php
require_once '../../includes/config.php';
$db = getDB();

if (isset($_POST['signUp'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    // Check if email already exists using prepared statement
    $checkEmail = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($checkEmail);
    $stmt->execute([$email]);
    
    if($stmt->rowCount() > 0){
        echo "<script>alert('Email Address Already exists'); window.location.href='index.php';</script>";
    }
    else{
        // Insert new user using prepared statement
        $insertQuery = "INSERT INTO users(name, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($insertQuery);
        
        try {
            $stmt->execute([$name, $email, $password]);
            echo "<script>alert('Thank you " . $name . "! Registration successful.'); window.location.href='index.php';</script>";
        } catch(PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
        }
    }       
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    // Login using prepared statement
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$email, $password]);
    
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch();
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        header("Location: homepage.php");
        exit();
    }
    else {
        echo "<script>alert('Incorrect email or password'); window.location.href='index.php';</script>";
    }
}
?>