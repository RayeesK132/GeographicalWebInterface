CREATE DATABASE IF NOT EXISTS map_dashboard DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE map_dashboard;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    department VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    category VARCHAR(100),
    department VARCHAR(100),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS map_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    default_zoom INT DEFAULT 13,
    default_center JSON,
    allowed_features JSON,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create admin user with full access
INSERT INTO users (
    email,
    password, -- This will be 'admin123' hashed
    name,
    status,
    role,
    department,
    permissions,
    profile_complete
) VALUES (
    'admin@bradford.gov.uk',
    '$2b$10$rNC5zkwEXzqrT8VQgnvk8.Cq/yVQI7opg9p9q4kYLiiVvZnSFUPBO', -- Hashed 'admin123'
    'System Administrator',
    'approved',
    'admin',
    'IT',
    '{"admin": true, "canManageUsers": true, "canManageAssets": true, "canApproveUsers": true, "canViewReports": true, "canEditSettings": true, "fullAccess": true}',
    true
);

-- Create department manager for IT
INSERT INTO users (
    email,
    password, -- This will be 'manager123' hashed
    name,
    status,
    role,
    department,
    permissions,
    profile_complete
) VALUES (
    'it.manager@bradford.gov.uk',
    '$2b$10$zNC5zkwEXzqrT8VQgnvk8.Cq/yVQI7opg9p9q4kYLiiVvZnSFUPBO', -- Hashed 'manager123'
    'IT Department Manager',
    'approved',
    'manager',
    'IT',
    '{"canManageAssets": true, "canViewReports": true, "canApproveUsers": false, "canEditSettings": false}',
    true
);

-- Create department manager for Facilities
INSERT INTO users (
    email,
    password, -- This will be 'manager123' hashed
    name,
    status,
    role,
    department,
    permissions,
    profile_complete
) VALUES (
    'facilities.manager@bradford.gov.uk',
    '$2b$10$zNC5zkwEXzqrT8VQgnvk8.Cq/yVQI7opg9p9q4kYLiiVvZnSFUPBO', -- Hashed 'manager123'
    'Facilities Department Manager',
    'approved',
    'manager',
    'Facilities',
    '{"canManageAssets": true, "canViewReports": true, "canApproveUsers": false, "canEditSettings": false}',
    true
);

-- Create department manager for Operations
INSERT INTO users (
    email,
    password, -- This will be 'manager123' hashed
    name,
    status,
    role,
    department,
    permissions,
    profile_complete
) VALUES (
    'operations.manager@bradford.gov.uk',
    '$2b$10$zNC5zkwEXzqrT8VQgnvk8.Cq/yVQI7opg9p9q4kYLiiVvZnSFUPBO', -- Hashed 'manager123'
    'Operations Department Manager',
    'approved',
    'manager',
    'Operations',
    '{"canManageAssets": true, "canViewReports": true, "canApproveUsers": false, "canEditSettings": false}',
    true
);

-- Grant all privileges to admin
UPDATE users 
SET permissions = JSON_SET(
    permissions,
    '$.fullAccess', true,
    '$.canDeleteAssets', true,
    '$.canExportData', true,
    '$.canImportData', true,
    '$.canManagePermissions', true
)
WHERE email = 'admin@bradford.gov.uk';

-- Add index for email searches
ALTER TABLE users ADD INDEX idx_email (email);

-- Add index for status searches
ALTER TABLE users ADD INDEX idx_status (status);

-- Initialize map settings
INSERT INTO map_settings (default_zoom, default_center, allowed_features) VALUES (
    13,
    '{"lat": 53.7960, "lng": -1.7520}',
    '{"addAssets": true, "deleteAssets": true, "editAssets": true}'
);
