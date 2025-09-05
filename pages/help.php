<?php
include_once '../includes/header.php';
?>

<div class="help-section">
    <p>
        <b>
            <i>
                Welcome to InternHub! Whether you're a student, recent graduate, or exploring new
                internship opportunities, we're excited to have you here.
                Click on the expandable sections below for a guide to get started.
                By following these steps, you will be well on your way to utilizing
                InternHub to effectively kickstart your career!
            </i>
        </b>
    </p>

    <details>
        <summary class="highlight">For Students</summary>

        <details>
            <summary>
                <h2><u>Getting Started with X</u></h2>
            </summary>
            <div class="step">
                <p><b>Registration:</b> Click "Register" button on the login page and
                    fill your student details.</p>
                <p><b>Login:</b> Click "Login" button and use your username and password
                    to access the system.</p>
            </div>
        </details>

        <details>
            <summary>
                <h3><u>Find & Apply for Internships</u></h3>
            </summary>
            <div class="step">
                <p><b>Searching:</b> Use search bar to find internships by
                    company,location or field of study.</p>
                <p><b>Applying:</b> Click "Apply" button on any internship listing, then
                    upload your resume and submit your application.</p>
            </div>
        </details>

        <details>
            <summary>
                <h4><u>Tracking Applications</u></h4>
            </summary>
            <div class="step">
                <p><b>Status:</b> Check your application status in "My Applications"
                    section.</p>
                <p><b>Updates:</b> You'll recieve an e-mail when your application status
                    changes.</p>
            </div>
        </details>

    </details>

    <details>
        <summary class="highlight">For Administration</summary>

        <details>
            <summary>
                <h5><u>System Management</u></h5>
            </summary>
            <div class="step">
                <p><b>Login:</b> Use admin credentials to access the system.</p>
                <p><b>User Management:</b> Add,edit or disable user accounts.</p>
            </div>
        </details>

        <details>
            <summary>
                <h6><u>Internship Management</u></h6>
            </summary>
            <div class="step">
                <p><b>Add Internships:</b> Click "Add New" in the internship section to
                    create new internship listings.</p>
                <p><b>Internship Listing Management:</b> Edit or remove internship
                    opportunities.</p>
            </div>
        </details>

        <details>
            <summary>
                <h2><u><b>Reports</b></u></h>
            </summary>
            <div class="step">
                <p><b>Generate Reports:</b> Make reports using application statistics
                    and other relavant information.</p>
                <p><b>Exporting Data:</b> Export reports as csv or pdf for further
                    analysis.</p>
            </div>
        </details>

    </details>

    <div class="help-section">
        <h7><u>FAQ</u></h7>

        <div class="faq_question">What if I forgot password?</div>
        <div class="faq_answer"><i>Click on "Forgot Password" link on login page and reset
                password using your e-mail address.</i></div>

        <div class="faq_question">How can I update my profile?</div>
        <div class="faq_answer"><i>Go to "My Profile" and edit personal information and upload
                relavant documents.</i></div>

        <div class="faq_question">Can I apply to as many internship vacancies I want?</div>
        <div class="faq_answer"><i>There is no limit to how many internship vacancies you can
                apply to but ensure that you provide a specific cover letter for each
                position you apply to.</i></div>

        <div class="faq_question">After submitting my online internship application,do I need to
            take any other action to ensure that my application is reviewed by specific
            company that I am interested in?</div>
        <div class="faq_answer"><i>You don't need to take any further action after submitting
                the application.You will recieve an e-mail notification as well as you
                can also check the status in your dashboard when your application status
                changes.</i></div>

        <div class="faq_question">Can I delete an application after submitting it?</div>
        <div class="faq_answer"><i>You can but within 24 hours you should delete the submitted
                application or contact support assistance.</i></div>

    </div>

    <div class="help-section">
        <h8><u><b>Contact Support</b></u></h8>
        <p>Still looking for something else?</p>

        <form class="contact-us-form">
            <div class="contact-us-input">
                <tr>
                    <td><b> Your Name: </b></td>
                    <td> <input type="text" name="name"> </td>
                </tr>
            </div>

            <div class="contact-us-input">
                <tr>
                    <td><b> Email Address: </b></td>
                    <td> <input type="email" name="email"> </td>
                </tr>
            </div>

            <div class="contact-us-input">
                <tr>
                    <td><b> Subject:</b></td>
                    <td> <input type="text" name="subject"> </td>
                </tr>
            </div>

            <div class="contact-us-input">
                <label for="message"><b> Message: </b></label>
                <textarea id="message" name="message" required> </textarea>
            </div>

            <button class="contact-submit-button" type="submit">Submit</button>

        </form>
    </div>

    <div class="contact_info">
        <h9><b>Connect with us on</b></h9>
        <p><b>Email:</b>support@InternHub.com</p>
        <p><b>Phone:</b>011-2159876</p>
        <p><b>Hours:</b>Weekdays,9.00 AM - 5.00 PM</p>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>