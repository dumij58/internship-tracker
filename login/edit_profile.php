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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
        }
        
        .form-section {
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        .form-group input[type="file"] {
            padding: 8px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
            width: 100%;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        
        .required {
            color: red;
        }
        
        .optional {
            color: #666;
            font-size: 14px;
        }
        
        .current-resume {
            background: #e8f5e8;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #4caf50;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">Edit User Profile</h1>
        
        <form method="POST" enctype="multipart/form-data">
            <!-- Basic Information Section -->
            <div class="form-section">
                <h2 class="section-title">Basic Information</h2>
                
                <div class="form-group">
                    <label for="full_name">Full Name <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userDetails['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone_number">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>" required>
                </div>
            </div>
            
            <!-- Academic Details Section -->
            <div class="form-section">
                <h2 class="section-title">Academic Details</h2>
                
                <div class="form-group">
                    <label for="university_college">University / College Name <span class="required">*</span></label>
                    <input type="text" id="university_college" name="university_college" value="<?php echo htmlspecialchars($userDetails['university_college']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="degree_program">Degree Program <span class="required">*</span></label>
                    <input type="text" id="degree_program" name="degree_program" value="<?php echo htmlspecialchars($userDetails['degree_program']); ?>" placeholder="e.g., BSc in Computer Science" required>
                </div>
                
                <div class="form-group">
                    <label for="year_of_study">Year of Study <span class="required">*</span></label>
                    <select id="year_of_study" name="year_of_study" required>
                        <option value="">Select Year</option>
                        <option value="1st year" <?php echo ($userDetails['year_of_study'] == '1st year') ? 'selected' : ''; ?>>1st Year</option>
                        <option value="2nd year" <?php echo ($userDetails['year_of_study'] == '2nd year') ? 'selected' : ''; ?>>2nd Year</option>
                        <option value="3rd year" <?php echo ($userDetails['year_of_study'] == '3rd year') ? 'selected' : ''; ?>>3rd Year</option>
                        <option value="4th year" <?php echo ($userDetails['year_of_study'] == '4th year') ? 'selected' : ''; ?>>4th Year</option>
                        <option value="Final year" <?php echo ($userDetails['year_of_study'] == 'Final year') ? 'selected' : ''; ?>>Final Year</option>
                        <option value="Graduate" <?php echo ($userDetails['year_of_study'] == 'Graduate') ? 'selected' : ''; ?>>Graduate</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="gpa">GPA / Academic Performance <span class="optional">(Optional)</span></label>
                    <input type="number" id="gpa" name="gpa" step="0.01" min="0" max="4" value="<?php echo $userDetails['gpa']; ?>" placeholder="e.g., 3.75">
                </div>
            </div>
            
            <!-- Skills & Interests Section -->
            <div class="form-section">
                <h2 class="section-title">Skills & Interests</h2>
                
                <div class="form-group">
                    <label for="key_skills">Key Skills <span class="required">*</span></label>
                    <textarea id="key_skills" name="key_skills" placeholder="e.g., Programming (Python, Java), Marketing, Design, etc." required><?php echo htmlspecialchars($userDetails['key_skills']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="areas_of_interest">Areas of Interest <span class="required">*</span></label>
                    <textarea id="areas_of_interest" name="areas_of_interest" placeholder="e.g., HR, Data Science, Research, etc." required><?php echo htmlspecialchars($userDetails['areas_of_interest']); ?></textarea>
                </div>
            </div>
            
            <!-- Resume/Portfolio Section -->
            <div class="form-section">
                <h2 class="section-title">Resume/Portfolio</h2>
                
                <?php if($userDetails['resume_path']): ?>
                    <div class="current-resume">
                        <strong>Current Resume:</strong> 
                        <a href="<?php echo htmlspecialchars($userDetails['resume_path']); ?>" target="_blank">View Current Resume</a>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="resume">Upload New Resume (PDF) <span class="optional">(Optional - only if you want to replace current resume)</span></label>
                    <input type="file" id="resume" name="resume" accept=".pdf">
                    <p class="optional">Please upload your resume in PDF format. Leave empty to keep current resume.</p>
                </div>
                
                <div class="form-group">
                    <label for="linkedin_url">LinkedIn URL <span class="optional">(Optional)</span></label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($userDetails['linkedin_url']); ?>" placeholder="https://linkedin.com/in/yourprofile">
                </div>
                
                <div class="form-group">
                    <label for="github_url">GitHub URL <span class="optional">(Optional)</span></label>
                    <input type="url" id="github_url" name="github_url" value="<?php echo htmlspecialchars($userDetails['github_url']); ?>" placeholder="https://github.com/yourusername">
                </div>
                
                <div class="form-group">
                    <label for="portfolio_url">Portfolio URL <span class="optional">(Optional)</span></label>
                    <input type="url" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($userDetails['portfolio_url']); ?>" placeholder="https://yourportfolio.com">
                </div>
            </div>
            
            <button type="submit" name="update_details" class="submit-btn">Update Profile</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="view_profile.php" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px;">Cancel</a>
        </div>
    </div>
</body>
</html>
