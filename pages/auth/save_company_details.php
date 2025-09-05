<?php
require_once '../../includes/config.php';
requireLogin(); 

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $company_name = $_POST['company_name'];
        $industry_type = $_POST['industry_type'];
        $company_website = $_POST['company_website'] ?: null;
        $official_email = $_POST['official_email'];
        $phone_number = $_POST['phone_number'];
        $company_address = $_POST['company_address'];
        $company_description = $_POST['company_description'];

        // Handle file upload for company logo
        $logo_path = null;
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../assets/images/company_logos/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
            $logo_filename = 'company_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_extension;
            $logo_path = $upload_dir . $logo_filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $logo_path)) {
                $logo_path = 'assets/images/company_logos/' . $logo_filename; // Relative path for database
            } else {
                throw new Exception('Failed to upload company logo');
            }
        }

        // Check if company profile already exists
        $check_profile = "SELECT id FROM company_profiles WHERE user_id = ?";
        $stmt = $db->prepare($check_profile);
        $stmt->execute([$_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            // Update existing profile
            $update_profile = "UPDATE company_profiles SET 
                company_name = ?, 
                industry_type = ?,
                company_website = ?, 
                company_description = ?, 
                address = ?,
                phone_number = ?
                WHERE user_id = ?";
            
            $stmt = $db->prepare($update_profile);
            $stmt->execute([
                $company_name,
                $industry_type,
                $company_website,
                $company_description,
                $company_address,
                $phone_number,
                $_SESSION['user_id']
            ]);
        } else {
            // Insert new profile
            $insert_profile = "INSERT INTO company_profiles 
                (user_id, company_name, industry_type, company_website, company_description, address, phone_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($insert_profile);
            $stmt->execute([
                $_SESSION['user_id'],
                $company_name,
                $industry_type,
                $company_website,
                $company_description,
                $company_address,
                $phone_number
            ]);
        }

        // Set session variable to indicate profile is complete
        $_SESSION['profile_complete'] = true;
        
        echo "<script>
            alert('Company details saved successfully!');
            window.location.href='../../index.php';
        </script>";
        logActivity('Company details saved successfully');

    } catch (Exception $e) {
        echo "<script>
            alert('Error saving company details: " . $e->getMessage() . "');
            window.location.href='company_details.php';
        </script>";
        logActivity('Error saving company details', $e->getMessage());
    }
} else {
    header("Location: company_details.php");
    exit();
}
?>
