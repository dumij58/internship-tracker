# Internship Tracker - User Details System

A PHP-based web application for managing user registration, login, and detailed user profiles for internship tracking.

## Features

- **User Registration & Login**: Secure user authentication system
- **User Details Form**: Comprehensive profile creation with the following sections:
  - **Basic Information**: Full name, phone number
  - **Academic Details**: University/college, degree program, year of study, GPA
  - **Skills & Interests**: Key skills and areas of interest
  - **Resume/Portfolio**: PDF resume upload, LinkedIn, GitHub, and portfolio links
- **Profile Management**: View and edit user profiles
- **File Upload**: Secure PDF resume upload functionality
- **Responsive Design**: Modern, mobile-friendly interface

## Database Setup

1. Create a MySQL database named `login`
2. Import the `database_setup.sql` file to create the required tables:
   - `users` table for basic user information
   - `user_details` table for comprehensive user profiles

## Installation

1. **Database Setup**:
   ```sql
   -- Run the database_setup.sql file in your MySQL server
   ```

2. **File Upload Directory**:
   - The system will automatically create an `uploads/` directory for resume files
   - Ensure the web server has write permissions to this directory

3. **Configuration**:
   - Update database connection details in `connect.php` if needed
   - The default configuration uses:
     - Host: localhost
     - User: root
     - Password: (empty)
     - Database: login

## File Structure

```
login/
├── index.php              # Main login/registration page
├── register.php           # Registration and login processing
├── homepage.php           # Dashboard after login
├── user_details.php       # User details form
├── view_profile.php       # View user profile
├── edit_profile.php       # Edit user profile
├── logout.php             # Logout functionality
├── connect.php            # Database connection
├── style.css              # Main stylesheet
├── background.jpg         # Background image
├── .htaccess              # Server configuration
├── database_setup.sql     # Database schema
└── uploads/               # Resume upload directory (auto-created)
```

## User Flow

1. **Registration**: Users register with name, email, and password
2. **Login**: Users log in with email and password
3. **Profile Completion**: After first login, users are prompted to complete their profile
4. **Profile Management**: Users can view and edit their profiles
5. **Dashboard**: Users see their profile status and can access various features

## Form Fields

### Basic Information
- Full Name (required)
- Phone Number (required)

### Academic Details
- University/College Name (required)
- Degree Program (required)
- Year of Study (required)
- GPA/Academic Performance (optional)

### Skills & Interests
- Key Skills (required)
- Areas of Interest (required)

### Resume/Portfolio
- Resume Upload - PDF only (required)
- LinkedIn URL (optional)
- GitHub URL (optional)
- Portfolio URL (optional)

## Security Features

- **Prepared Statements**: All database queries use prepared statements to prevent SQL injection
- **File Upload Security**: Only PDF files are allowed for resume uploads
- **Session Management**: Secure session handling for user authentication
- **Input Validation**: Server-side validation for all form inputs
- **XSS Protection**: HTML escaping for all user-generated content

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design for mobile devices
- JavaScript enabled for enhanced functionality

## Troubleshooting

1. **File Upload Issues**: Ensure the `uploads/` directory has proper write permissions
2. **Database Connection**: Verify database credentials in `connect.php`
3. **Session Issues**: Check PHP session configuration
4. **File Size Limits**: Adjust upload limits in `.htaccess` if needed

## Customization

- Modify `style.css` for custom styling
- Update form fields in `user_details.php` and `edit_profile.php`
- Add new database fields in `database_setup.sql`
- Customize validation rules in PHP files

## License

This project is open source and available under the MIT License.
