Here’s a README template that explains how the code works and how to set up and run the project:

---

# **User Management System with Email Verification**

## **Overview**

This project is a user management system that includes features like:
- User registration with email and phone number.
- Email verification.
- Admin panel for user management (approve or reject users).
- Logging of user activities.
- Asset management functionality.
- Real-time updates of user permissions based on admin actions.

## **Features**

- **User Registration**: Users can register with a username, email, and phone number. They must verify their email address before their account is approved.
- **Email Verification**: A verification email is sent to the user after registration, and they need to verify their email to activate the account.
- **Admin Dashboard**: Admins can approve or reject users, manage user roles, and configure user access controls.
- **Asset Management**: Users can add assets to the system, and assets are displayed on an interactive map.
- **Logging**: All user actions (login, registration, asset management) are logged for security and auditing purposes.

---

## **Technologies Used**

- **PHP**: Server-side scripting language.
- **MySQL**: Relational database management system.
- **PHPMailer**: Library to send emails for email verification.
- **Leaflet.js**: Library for embedding maps and managing markers.
- **CSS**: Styling for the user interface.

---

## **Setup Instructions**

### **1. Clone the Repository**

First, clone the repository to your local machine:

```bash
git clone https://github.com/your-repository/user-management-system.git
```

### **2. Install Dependencies**

The project uses **PHPMailer** for email functionality. Use **Composer** to install dependencies:

```bash
composer install
```

### **3. Database Setup**

Create a database named `userDB` and run the following SQL queries to set up the required tables.

```sql
-- Users Table
CREATE TABLE `users` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    `approved` TINYINT(1) NOT NULL DEFAULT 0,
    `verification_code` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `add_visible` TINYINT(1) DEFAULT 0,
    `edit_visible` TINYINT(1) DEFAULT 0,
    `delete_visible` TINYINT(1) DEFAULT 0,
    `filter_visible` TINYINT(1) DEFAULT 0
);

-- Logs Table
CREATE TABLE `logs` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL,
    `action` TEXT NOT NULL,
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Assets Table
CREATE TABLE `assets` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `latitude` DECIMAL(10, 8) NOT NULL,
    `longitude` DECIMAL(11, 8) NOT NULL,
    `owner` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### **4. Configure Environment Variables**

Create a `.env` file in the root of the project and configure the following environment variables:

```ini
DB_HOST=localhost
DB_NAME=userDB
DB_USER=root
DB_PASSWORD=
SMTP_HOST=smtp.example.com
SMTP_PORT=587
SMTP_USER=your_email@example.com
SMTP_PASSWORD=your_email_password
```

### **5. Email Configuration**

In the `email.php` file, ensure that the SMTP configuration is correct. Update the `sendVerificationEmail` function to use your email SMTP details.

```php
$mail->Host = 'smtp.example.com';
$mail->Username = 'your_email@example.com';
$mail->Password = 'your_email_password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

### **6. Run the Project**

Ensure that your local PHP server is running and configured to handle `.php` files. If you’re using XAMPP, you can run it by navigating to the project folder and accessing `index.php` via:

```
http://localhost/user-management-system/index.php
```

---

## **File Structure**

### **1. `index.php`**

- **Purpose**: The main landing page for logged-in users.
- Displays a welcome message and links to other sections (e.g., logout).

### **2. `login.php`**

- **Purpose**: User login page.
- Users can enter their credentials (username and password) to authenticate.

### **3. `signup.php`**

- **Purpose**: User registration page.
- Users provide a username, email, phone number, and password. They also receive a verification email.

### **4. `verify-email.php`**

- **Purpose**: Used to verify the user’s email using a verification code.
- The verification code is generated when a user registers and sent via email.

### **5. `user-management.php`**

- **Purpose**: Admin interface for managing users.
- Allows the admin to approve/reject users, assign roles, and manage their permissions.

### **6. `approve-user.php`**

- **Purpose**: Admin functionality to approve users.
- Admins can approve a user, changing their `approved` status in the database.

### **7. `assets.php`**

- **Purpose**: Allows users to manage and view assets.
- Users can add assets and see them on an interactive map.

### **8. `log.php`**

- **Purpose**: Handles logging actions (e.g., user registration, login).
- Used for tracking user activities in the database.

### **9. `db.php`**

- **Purpose**: Establishes a connection to the MySQL database.
- Used across the project to interact with the database.

---

## **Workflow**

1. **User Registration**: 
    - Users can register through `signup.php`, entering their username, email, phone, and password.
    - After registration, they receive a verification email with a unique verification link.
    - Once the user clicks the link in the email, the `verify-email.php` page marks their email as verified.
    
2. **Admin Approval**: 
    - Admins can manage users from `user-management.php`. They can approve users by clicking a button that calls `approve-user.php`.
    - After approval, the user can log in.

3. **Asset Management**: 
    - Users can add assets via `assets.php`, which places markers on an interactive map using Leaflet.js.
    - Asset data is saved in the database and retrieved via `get-assets.php` to display on the map.
    
4. **Logging**: 
    - Actions like user registration, login, and asset management are logged into the `logs` table for auditing purposes.

---

## **Troubleshooting**

- **Email not sent**: Ensure that the SMTP settings are configured correctly in `email.php`. Test with a valid email provider.
- **Database connection error**: Double-check the database credentials in the `.env` file and ensure that the MySQL server is running.
- **Assets not saving**: Ensure that the `save-asset.php` script is working correctly by checking your PHP error logs and reviewing the database for errors.

--- 

This README provides a comprehensive guide to setting up and using the project, including database setup, environment configuration, and a high-level explanation of the file structure. Let me know if you'd like any changes or additions!