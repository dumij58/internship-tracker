<?php
session_start();
include 'connect.php';

// Check if user is logged in
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get user details
$user_id = null;
$checkUser = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($checkUser);
$stmt->execute([$_SESSION['email']]);
if($stmt->rowCount() > 0) {
    $user = $stmt->fetch();
    $user_id = $user['id'];
    
    // Get user details
    $getDetails = "SELECT * FROM user_details WHERE user_id = ?";
    $stmt = $conn->prepare($getDetails);
    $stmt->execute([$user_id]);
    if($stmt->rowCount() > 0) {
        $userDetails = $stmt->fetch();
    } else {
        header("Location: user_details.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Handle form submission for updates
if(isset($_POST['update_details'])) {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $university_college = $_POST['university_college'];
    $degree_program = $_POST['degree_program'];
    $year_of_study = $_POST['year_of_study'];
    $gpa = $_POST['gpa'] ? $_POST['gpa'] : null;
    $key_skills = $_POST['key_skills'];
    $areas_of_interest = $_POST['areas_of_interest'];
    $linkedin_url = $_POST['linkedin_url'];
    $github_url = $_POST['github_url'];
    $portfolio_url = $_POST['portfolio_url'];
    
    // Handle file upload if new resume is uploaded
    $resume_path = $userDetails['resume_path']; // Keep existing path
    if(isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed = ['pdf'];
        $filename = $_FILES['resume']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $upload_dir = 'uploads/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = 'resume_' . $user_id . '_' . time() . '.pdf';
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['resume']['tmp_name'], $upload_path)) {
                // Delete old resume file if exists
                if($resume_path && file_exists($resume_path)) {
                    unlink($resume_path);
                }
                $resume_path = $upload_path;
            }
        }
    }
    
    // Update user details
    $updateQuery = "UPDATE user_details SET full_name = ?, phone_number = ?, university_college = ?, degree_program = ?, year_of_study = ?, gpa = ?, key_skills = ?, areas_of_interest = ?, resume_path = ?, linkedin_url = ?, github_url = ?, portfolio_url = ? WHERE user_id = ?";
    $stmt = $conn->prepare($updateQuery);
    
    try {
        $stmt->execute([$full_name, $phone_number, $university_college, $degree_program, $year_of_study, $gpa, $key_skills, $areas_of_interest, $resume_path, $linkedin_url, $github_url, $portfolio_url, $user_id]);
        echo "<script>alert('Profile updated successfully!'); window.location.href='view_profile.php';</script>";
        // Refresh user details
        $getDetails = "SELECT * FROM user_details WHERE user_id = ?";
        $stmt = $conn->prepare($getDetails);
        $stmt->execute([$user_id]);
        $userDetails = $stmt->fetch();
    } catch(PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
        }
        
        .profile-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: #f9f9f9;
        }
        
        .section-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
        }
        
        .edit-btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 5px;
        }
        
        .resume-link {
            color: #667eea;
            text-decoration: none;
        }
        
        .resume-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">User Profile</h1>
        
        <!-- Basic Information Section -->
        <div class="profile-section">
            <h2 class="section-title">Basic Information</h2>
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($userDetails['full_name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($userDetails['phone_number']); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Academic Details Section -->
        <div class="profile-section">
            <h2 class="section-title">Academic Details</h2>
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">University / College</div>
                    <div class="info-value"><?php echo htmlspecialchars($userDetails['university_college']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Degree Program</div>
                    <div class="info-value"><?php echo htmlspecialchars($userDetails['degree_program']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Year of Study</div>
                    <div class="info-value"><?php echo htmlspecialchars($userDetails['year_of_study']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">GPA</div>
                    <div class="info-value"><?php echo $userDetails['gpa'] ? htmlspecialchars($userDetails['gpa']) : 'Not specified'; ?></div>
                </div>
            </div>
        </div>
        
        <!-- Skills & Interests Section -->
        <div class="profile-section">
            <h2 class="section-title">Skills & Interests</h2>
            <div class="profile-info">
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Key Skills</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($userDetails['key_skills'])); ?></div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Areas of Interest</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($userDetails['areas_of_interest'])); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Resume/Portfolio Section -->
        <div class="profile-section">
            <h2 class="section-title">Resume/Portfolio</h2>
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">Resume</div>
                    <div class="info-value">
                        <?php if($userDetails['resume_path']): ?>
                            <a href="<?php echo htmlspecialchars($userDetails['resume_path']); ?>" target="_blank" class="resume-link">View Resume</a>
                        <?php else: ?>
                            No resume uploaded
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">LinkedIn</div>
                    <div class="info-value">
                        <?php if($userDetails['linkedin_url']): ?>
                            <a href="<?php echo htmlspecialchars($userDetails['linkedin_url']); ?>" target="_blank" class="resume-link">View Profile</a>
                        <?php else: ?>
                            Not provided
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">GitHub</div>
                    <div class="info-value">
                        <?php if($userDetails['github_url']): ?>
                            <a href="<?php echo htmlspecialchars($userDetails['github_url']); ?>" target="_blank" class="resume-link">View Profile</a>
                        <?php else: ?>
                            Not provided
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Portfolio</div>
                    <div class="info-value">
                        <?php if($userDetails['portfolio_url']): ?>
                            <a href="<?php echo htmlspecialchars($userDetails['portfolio_url']); ?>" target="_blank" class="resume-link">View Portfolio</a>
                        <?php else: ?>
                            Not provided
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
            <a href="homepage.php" class="back-btn">Back to Homepage</a>
        </div>
    </div>
</body>
</html>
