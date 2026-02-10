#!/bin/bash
# Quick database setup script

echo "=== SmartCampus Database Setup ==="
echo ""

# Check if MySQL is running
if ! systemctl is-active --quiet mysql && ! systemctl is-active --quiet mariadb; then
    echo "⚠️  MySQL/MariaDB service is not running."
    echo "Starting MySQL service..."
    sudo systemctl start mysql || sudo systemctl start mariadb
fi

echo "Creating database and user..."
echo ""

# Create a temporary SQL file
cat > /tmp/smart_campus_setup.sql << 'EOF'
-- Create database
CREATE DATABASE IF NOT EXISTS smart_campus;

-- Create user (drop first if exists to avoid errors)
DROP USER IF EXISTS 'smart_campus'@'localhost';
CREATE USER 'smart_campus'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON smart_campus.* TO 'smart_campus'@'localhost';
FLUSH PRIVILEGES;

-- Use the database
USE smart_campus;

-- Create tables
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    role ENUM('admin', 'super-admin', 'head_teacher', 'technician', 'teacher', 'student', 'parent', 'marketing', 'staff') NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    student_id_number VARCHAR(20) UNIQUE,
    department VARCHAR(100),
    current_year INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_name VARCHAR(100) NOT NULL,
    serial_number VARCHAR(50) UNIQUE,
    location VARCHAR(100),
    status ENUM('operational', 'repairing', 'maintenance', 'broken') DEFAULT 'operational',
    last_checked TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS maintenance_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_id INT NOT NULL,
    technician_id INT,
    issue_description TEXT,
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('pending', 'in-progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    FOREIGN KEY (technician_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    task_name VARCHAR(100),
    score DECIMAL(5,2),
    weight INT,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    status ENUM('present', 'absent', 'late') DEFAULT 'present',
    attendance_date DATE DEFAULT CURDATE(),
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attendance (student_id, attendance_date),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS parent_student (
    parent_user_id INT NOT NULL,
    student_id INT NOT NULL,
    PRIMARY KEY (parent_user_id, student_id),
    FOREIGN KEY (parent_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;
EOF

echo "Running SQL setup..."
sudo mysql < /tmp/smart_campus_setup.sql

if [ $? -eq 0 ]; then
    echo "✅ Database and tables created successfully!"
    echo ""
    echo "Now creating users with PHP to ensure correct password hashing..."
    
    # Create a PHP script to insert users with proper password hashing
    cat > /tmp/insert_users.php << 'PHPEOF'
<?php
$pdo = new PDO('mysql:host=localhost;dbname=smart_campus', 'smart_campus', 'password123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Generate proper password hash
$hash = password_hash('password123', PASSWORD_DEFAULT);

// Clear existing users
$pdo->exec("DELETE FROM students");
$pdo->exec("DELETE FROM users");

// Insert users
$stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, full_name) VALUES (?, ?, ?, ?, ?)");

$users = [
    ['admin_user', $hash, 'admin@campus.com', 'admin', 'System Administrator'],
    ['tech_mike', $hash, 'mike@tech.com', 'technician', 'Mike Repairman'],
    ['teacher_jane', $hash, 'jane@campus.com', 'teacher', 'Jane Doe'],
    ['parent_smith', $hash, 'smith@parent.com', 'parent', 'John Smith'],
    ['student_alex', $hash, 'alex@student.com', 'student', 'Alex Thompson']
];

foreach ($users as $user) {
    $stmt->execute($user);
}

// Get student_alex user_id
$student_user_id = $pdo->query("SELECT id FROM users WHERE username='student_alex'")->fetchColumn();

// Insert student record
$pdo->exec("INSERT INTO students (user_id, student_id_number, department, current_year) 
           VALUES ($student_user_id, 'SC-2026-001', 'Computer Science', 3)");

// Insert sample assets
$pdo->exec("INSERT INTO assets (asset_name, serial_number, location, status) VALUES
           ('Projector P-04', 'SN-99221', 'Lab 202', 'operational'),
           ('Smart Board SB-12', 'SN-88110', 'Hall A', 'repairing')");

echo "✅ Users created successfully!\n";
echo "Password hash used: " . substr($hash, 0, 30) . "...\n";
PHPEOF

    php /tmp/insert_users.php
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "=========================================="
        echo "✅ SETUP COMPLETE!"
        echo "=========================================="
        echo ""
        echo "You can now login with:"
        echo "  Username: admin_user"
        echo "  Password: password123"
        echo ""
        echo "Open in Brave: http://localhost:8000/login.php"
        echo ""
    else
        echo "❌ Failed to create users"
        echo "Try running: http://localhost:8000/setup_db.php"
    fi
    
    # Cleanup
    rm /tmp/insert_users.php
else
    echo "❌ Failed to setup database"
    echo "You may need to enter your MySQL root password"
fi

rm /tmp/smart_campus_setup.sql
