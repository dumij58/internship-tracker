<?php
session_start();
include 'connect.php';

// Check if user is logged in
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Check if user has completed their details
$user_id = null;
$checkUser = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($checkUser);
$stmt->execute([$_SESSION['email']]);
if($stmt->rowCount() > 0) {
    $user = $stmt->fetch();
    $user_id = $user['id'];
    
    // Check if user details exist
    $checkDetails = "SELECT * FROM user_details WHERE user_id = ?";
    $stmt = $conn->prepare($checkDetails);
    $stmt->execute([$user_id]);
    $hasDetails = $stmt->rowCount() > 0;
} else {
    $hasDetails = false;
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
        
        <?php if(!$hasDetails): ?>
            <div style="margin: 20px 0; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
                <p style="margin: 0; color: #856404; font-weight: bold;">Please complete your user details to continue.</p>
            </div>
            <a href="user_details.php" style="background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; transition: background 0.3s; display: inline-block; margin: 10px;">Complete Profile</a>
        <?php else: ?>
            <div style="margin: 20px 0; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                <p style="margin: 0; color: #155724; font-weight: bold;">✓ Your profile is complete!</p>
            </div>
            <a href="view_profile.php" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; transition: background 0.3s; display: inline-block; margin: 10px;">View Profile</a>
        <?php endif; ?>
        
        <a href="logout.php" class="logout-btn">Logout</a>
   </div> 
</body>
</html>