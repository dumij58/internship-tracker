<?php
require_once '../../includes/config.php';
requireLogin();

$page_title = 'Company Details';
require_once '../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Company Registration Details</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="save_company_details.php" enctype="multipart/form-data">
                        <!-- Company Information -->
                        <h4 class="mb-3">Company Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="industry_type" class="form-label">Industry Type *</label>
                                <select class="form-control" id="industry_type" name="industry_type" required>
                                    <option value="">Select Industry</option>
                                    <option value="IT">Information Technology</option>
                                    <option value="Finance">Finance & Banking</option>
                                    <option value="Marketing">Marketing & Advertising</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                    <option value="Retail">Retail & E-commerce</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Consulting">Consulting</option>
                                    <option value="Non-profit">Non-profit</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_website" class="form-label">Company Website</label>
                                <input type="url" class="form-control" id="company_website" name="company_website" placeholder="https://www.example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_logo" class="form-label">Company Logo (Optional)</label>
                                <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <h4 class="mb-3 mt-4">Contact Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="official_email" class="form-label">Official Email Address *</label>
                                <input type="email" class="form-control" id="official_email" name="official_email" value="<?php echo $_SESSION['email']; ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="company_address" class="form-label">Company Address (Head Office / Main Branch) *</label>
                                <textarea class="form-control" id="company_address" name="company_address" rows="3" placeholder="Enter complete address including street, city, state, and postal code" required></textarea>
                            </div>
                        </div>

                        <!-- Company Description -->
                        <h4 class="mb-3 mt-4">Company Description</h4>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="company_description" class="form-label">Company Description *</label>
                                <textarea class="form-control" id="company_description" name="company_description" rows="4" placeholder="Brief description of your company, its mission, and what makes it unique" required></textarea>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Company Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
