<?php
require_once '../../includes/config.php';
requireLogin(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $university = $_POST['university'];
        $degree_program = $_POST['degree_program'];
        $year_of_study = $_POST['year_of_study'];
        $gpa = $_POST['gpa'] ?: null;
        $key_skills = $_POST['key_skills'];
        $areas_of_interest = $_POST['areas_of_interest'];
        $portfolio_links = $_POST['portfolio_links'] ?: null;

        // Handle resume upload
        $resume_path = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../uploads/resumes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
            $file_name = 'resume_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $upload_path)) {
                $resume_path = 'uploads/resumes/' . $file_name;
            }
        }

        // Update user password if provided
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_user = "UPDATE users SET password_hash = ? WHERE user_id = ?";
            $stmt = $db->prepare($update_user);
            $stmt->execute([$hashed_password, $_SESSION['user_id']]);
        }

        // Check if student profile already exists
        $check_profile = "SELECT id FROM student_profiles WHERE user_id = ?";
        $stmt = $db->prepare($check_profile);
        $stmt->execute([$_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            // Update existing profile
            $update_profile = "UPDATE student_profiles SET 
                first_name = ?, 
                last_name = ?, 
                phone = ?, 
                university = ?, 
                major = ?, 
                year_of_study = ?, 
                gpa = ?, 
                resume_path = ?, 
                portfolio_url = ?, 
                skills = ?, 
                bio = ?
                WHERE user_id = ?";
            
            $stmt = $db->prepare($update_profile);
            $stmt->execute([
                $full_name,
                '', // last_name (empty for now)
                $phone,
                $university,
                $degree_program,
                $year_of_study,
                $gpa,
                $resume_path,
                $portfolio_links,
                $key_skills,
                $areas_of_interest,
                $_SESSION['user_id']
            ]);
        } else {
            // Insert new profile
            $insert_profile = "INSERT INTO student_profiles 
                (user_id, first_name, last_name, phone, university, major, year_of_study, gpa, resume_path, portfolio_url, skills, bio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($insert_profile);
            $stmt->execute([
                $_SESSION['user_id'],
                $full_name,
                '', // last_name (empty for now)
                $phone,
                $university,
                $degree_program,
                $year_of_study,
                $gpa,
                $resume_path,
                $portfolio_links,
                $key_skills,
                $areas_of_interest
            ]);
        }

        // Set session variable to indicate profile is complete
        $_SESSION['profile_complete'] = true;
        
        echo "<script>
            alert('User details saved successfully!');
            window.location.href='../../pages/index.php';
        </script>";
        
    } catch (Exception $e) {
        echo "<script>
            alert('Error saving user details: " . $e->getMessage() . "');
            window.location.href='user_details.php';
        </script>";
    }
} else {
    header("Location: user_details.php");
    exit();
}
?>
