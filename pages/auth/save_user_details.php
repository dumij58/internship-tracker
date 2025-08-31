<?php
require_once '../../includes/config.php';
requireLogin(); 

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $university = $_POST['university'];
        $degree_program = $_POST['degree_program'];
        $year_of_study = $_POST['year_of_study'];
        $gpa = $_POST['gpa'] ?: null;
        $key_skills = $_POST['key_skills'];
        $areas_of_interest = $_POST['areas_of_interest'];
        $portfolio_links = $_POST['portfolio_links'] ?: null;

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
                $portfolio_links,
                $key_skills,
                $areas_of_interest,
                $_SESSION['user_id']
            ]);
        } else {
            // Insert new profile
            $insert_profile = "INSERT INTO student_profiles 
                (user_id, first_name, last_name, phone, university, major, year_of_study, gpa, portfolio_url, skills, bio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
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
        logActivity('User details saved successfully');

    } catch (Exception $e) {
        echo "<script>
            alert('Error saving user details: " . $e->getMessage() . "');
            window.location.href='user_details.php';
        </script>";
        logActivity('Error saving user details', $e->getMessage());
    }
} else {
    header("Location: user_details.php");
    exit();
}
?>
