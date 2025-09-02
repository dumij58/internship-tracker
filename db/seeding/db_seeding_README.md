# Analytics Sample Data Generator

This directory contains scripts to generate and remove sample data for testing the analytics dashboard.

## Files

- **`seed-analytics-data.php`** - Generates comprehensive sample data for analytics testing
- **`unseed-analytics-data.php`** - Removes all sample data and restores the database to its original state

## Usage

### Generating Sample Data

1. Navigate to: `http://localhost/internship-tracker/scripts/seed-analytics-data.php`
2. The script will generate:
   - 40 Student users with profiles
   - 15 Company users with profiles  
   - 25 Internship postings
   - 80 Applications with various statuses
   - 50 System log entries
   - 30 Notifications

### Removing Sample Data

1. Navigate to: `http://localhost/internship-tracker/scripts/unseed-analytics-data.php`
2. The script will remove all sample data while preserving:
   - Original admin user (admin@example.com)
   - Original student user (student@example.com)
   - Original company user (company@example.com)
   - Default student profile

## Sample Data Details

### Students (40 users)
- Usernames: student001 to student040
- Email: student1@university.edu to student40@university.edu
- Password: student123 (for all)
- Profiles include: various universities, majors, GPAs, skills
- Registration dates: spread over the last 8 months

### Companies (15 users)
- Usernames: company001 to company015
- Email: hr@company1.com to hr@company15.com
- Password: company123 (for all)
- Profiles include: realistic company names, websites, addresses
- Random verification status

### Internships (25 postings)
- Various job titles (Software Developer, UI/UX Designer, etc.)
- Different statuses (draft, published, closed, cancelled)
- Random locations across Sri Lanka
- Mixed paid/unpaid positions
- Different experience levels and duration

### Applications (80 applications)
- Random combinations of students and internships
- Various statuses (submitted, under_review, shortlisted, rejected, accepted, withdrawn)
- Realistic application dates
- No duplicate applications per student-internship pair

### System Logs (50 entries)
- Various user actions (login, logout, profile updates, etc.)
- Distributed across all user types
- Realistic timestamps

### Notifications (30 entries)
- Different types (info, success, warning, error)
- Various titles and messages
- Random read/unread status

## Testing the Analytics

After running the seed script, you can test various analytics features:

1. **Overview Statistics** - See realistic user/application/company counts
2. **Status Distributions** - View pie charts with actual data
3. **University/Major Analytics** - See top universities and majors
4. **Trends** - View registration and application trends over time
5. **Performance Metrics** - GPA statistics, stipend ranges, etc.

## Security Notes

- Both scripts require admin authentication
- Scripts use transactions to ensure data integrity
- Error handling prevents partial data corruption
- Original system data is preserved during cleanup

## Database Reset

If you need to completely reset the database:

1. Run the unseed script first
2. Re-import the original schema.sql file
3. This will restore the database to its initial state with just the 3 default users

## Troubleshooting

If you encounter errors:

1. Check database connection settings in `includes/config.php`
2. Ensure you're logged in as an admin user
3. Check PHP error logs for detailed error messages
4. Verify database permissions for creating/deleting records

## Performance Notes

- Sample data generation takes 10-30 seconds depending on server performance
- Large datasets may require increased PHP execution time limits
- Consider running during low-traffic periods for production systems
