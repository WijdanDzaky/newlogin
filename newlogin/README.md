# Login & Registration System

A complete login and registration system built with PHP and MySQL, designed to run on XAMPP localhost.

## Features

- User registration with validation
- User login with password hashing
- Session management
- Dashboard for authenticated users
- Responsive design
- AJAX-based form submission

## File Structure

```
newlogin/
├── index.php                      # Login/Registration forms
├── verify_otp.php                 # OTP verification page
├── dashboard.php                  # Protected dashboard page
├── README.md                       # Documentation
├── EMAIL_SETUP.md                 # Email configuration guide
├── includes/
│   ├── config.php                # Database & email configuration
│   ├── login.php                 # Login handler
│   ├── register.php              # Registration handler
│   ├── verify_otp.php            # OTP verification handler
│   ├── resend_otp.php            # Resend OTP handler
│   ├── get_otp_time.php          # Get OTP expiration time
│   └── logout.php                # Logout handler
└── assets/
    ├── css/
    │   ├── style.css             # Login/Register page styling
    │   └── dashboard.css         # Dashboard styling
    └── js/
        └── script.js             # Form handling and validation
```

## Setup Instructions

### Prerequisites
- XAMPP installed and running
- PHP 7.0 or higher
- MySQL database
- **Gmail account (untuk OTP email)**

### Email Configuration (IMPORTANT!)

Sebelum menggunakan sistem, setup email terlebih dahulu:

1. Buka file `EMAIL_SETUP.md`
2. Ikuti langkah-langkah untuk setup Gmail App Password
3. Edit `includes/config.php` dan masukkan email & password Anda

Tanpa konfigurasi email, sistem registrasi tidak akan bekerja!

### Installation Steps

1. **Ensure XAMPP is running**
   - Start Apache and MySQL from XAMPP Control Panel

2. **Database Setup**
   - The database will be automatically created on first access
   - Database name: `login_system`
   - Table: `users` (auto-created with columns: id, username, email, password, created_at)

3. **Access the Application**
   - Open your browser and navigate to: `http://localhost/newlogin/`
   - You should see the login page

### Using the Application

#### Registration
1. Click "Sign up" link on the login page
2. Fill in the registration form:
   - Username (minimum 3 characters)
   - Email (valid email format - preferably Gmail)
   - Password (minimum 6 characters)
   - Confirm Password
3. Click "Sign Up" button
4. **Check your email for OTP code**
5. Enter the 6-digit OTP in the verification page
6. OTP adalah valid untuk 10 menit
7. Anda bisa klik "Resend OTP" jika tidak menerima atau OTP expired

#### Login
1. Enter your username and password
2. Click "Login" button
3. After successful login, you'll be redirected to the dashboard
4. Your user information will be displayed on the dashboard

#### Logout
1. Click the "Logout" button on the dashboard navbar
2. You'll be redirected to the login page

## Database Details

### Users Table Structure
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    otp VARCHAR(6),
    otp_expires_at DATETIME,
    is_verified INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

### View Data in phpMyAdmin
1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Select database: `login_system`
3. Select table: `users`
4. You'll see all registered users and their details

## Security Features

- Password hashing using PHP's `password_hash()` function
- SQL injection prevention using prepared statements
- Session-based authentication
- **OTP-based email verification for registration**
- Input validation and sanitization
- CSRF protection through POST method
- OTP expires after 10 minutes
- Email verification required before login

## Troubleshooting

### Database Connection Error
- Check if MySQL is running in XAMPP Control Panel
- Verify database credentials in `includes/config.php`
- Default: username = "root", password = ""

### No Database Appearing in phpMyAdmin
- Refresh the page in phpMyAdmin
- The database is created automatically on first page load

### Session Not Working
- Ensure PHP's session support is enabled
- Check browser cookies are enabled

## File Permissions

If you encounter permission errors:
1. Right-click the newlogin folder in Windows Explorer
2. Properties → Security → Edit → Select your user → Full Control

## Default Configuration

- **Database Server**: localhost
- **Database User**: root
- **Database Password**: (empty)
- **Database Name**: login_system

You can modify these in `includes/config.php` if needed.

## Next Steps

You can now:
1. Customize the dashboard with your main web content
2. Add additional features (user profiles, email verification, password reset)
3. Implement role-based access control
4. Add more functionality to the dashboard

## Support

For issues or questions, check:
- Browser console for JavaScript errors
- PHP error logs in XAMPP
- Database connection status in phpMyAdmin

---
**Last Updated**: December 7, 2025
