<?php
session_start();
include 'connect.php';

if (isset($_POST['signUp'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        echo "<script>alert('Email Address Already exists'); window.location.href='index.php';</script>";
    }
    else{
        $insertQuery = "INSERT INTO users(name, email, password) VALUES ('$name', '$email', '$password')";
        if($conn->query($insertQuery) == TRUE){
            echo "<script>alert('Thank you " . $name . "! Registration successful.'); window.location.href='index.php';</script>";
        }
        else{
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='index.php';</script>";
        }
    }       
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
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