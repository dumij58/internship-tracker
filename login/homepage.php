<?php
session_start();
include 'connect.php';

// Check if user is logged in
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        .welcome-text {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .logout-btn {
            background: #ff4757;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #ff3742;
        }
    </style>
</head>
<body>
   <div class="welcome-container">
        <p class="welcome-text">
            Hi <?php echo $_SESSION['name']; ?>!
        </p>
        <a href="logout.php" class="logout-btn">Logout</a>
   </div> 
</body>
</html>