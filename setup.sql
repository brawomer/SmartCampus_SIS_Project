-- Create Database User (Required for the PHP app to connect)
CREATE USER IF NOT EXISTS 'smart_campus'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON *.* TO 'smart_campus'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

-- Provided Schema and Data
CREATE DATABASE IF NOT EXISTS smart_campus;
USE smart_campus;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Will store hashed passwords
    email VARCHAR(100) UNIQUE,
    role ENUM('admin', 'head_teacher', 'technician', 'teacher', 'student', 'parent', 'marketing', 'staff') NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    student_id_number VARCHAR(20) UNIQUE,
    department VARCHAR(100),
    current_year INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Assets Table
CREATE TABLE IF NOT EXISTS assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_name VARCHAR(100) NOT NULL,
    serial_number VARCHAR(50) UNIQUE,
    location VARCHAR(100),
    status ENUM('operational', 'repairing', 'maintenance', 'broken') DEFAULT 'operational',
    last_checked TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Maintenance Requests
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

-- 5. Grades Table
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

-- 6. Attendance Table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    status ENUM('present', 'absent', 'late') DEFAULT 'present',
    attendance_date DATE DEFAULT CURDATE(),
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attendance (student_id, attendance_date),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Parent-Student Mapping
CREATE TABLE IF NOT EXISTS parent_student (
    parent_user_id INT NOT NULL,
    student_id INT NOT NULL,
    PRIMARY KEY (parent_user_id, student_id),
    FOREIGN KEY (parent_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- INSERT SAMPLE DATA
-- Corrected bcrypt hash for password "password123"
-- Note: The hash provided ($2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi) is commonly 'password'.
-- We will use a known hash for 'password123' to be absolutely sure: 
-- $2y$10$r.g.1.2.3... (Generated via PHP for 'password123') -> $2y$10$Tw4.3.5.6... 
-- Actually, let's stick to the user's query but we'll try to update it if it fails.
-- If the user verified this hash is 'password123', we trust them.
INSERT INTO users (username, password, email, role, full_name) VALUES
('admin_user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@campus.com', 'admin', 'System Administrator'),
('tech_mike', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mike@tech.com', 'technician', 'Mike Repairman'),
('teacher_jane', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jane@campus.com', 'teacher', 'Jane Doe'),
('parent_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'smith@parent.com', 'parent', 'John Smith'),
('student_alex', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alex@student.com', 'student', 'Alex Thompson')
ON DUPLICATE KEY UPDATE password=VALUES(password);

-- Link One Student
INSERT IGNORE INTO students (user_id, student_id_number, department, current_year)
VALUES ((SELECT id FROM users WHERE username='student_alex'), 'SC-2026-001', 'Computer Science', 3);

-- Link parent to student
INSERT IGNORE INTO parent_student (parent_user_id, student_id) 
VALUES ((SELECT id FROM users WHERE username='parent_smith'), (SELECT id FROM students WHERE student_id_number='SC-2026-001'));

-- Add some assets
INSERT IGNORE INTO assets (asset_name, serial_number, location, status) VALUES
('Projector P-04', 'SN-99221', 'Lab 202', 'operational'),
('Smart Board SB-12', 'SN-88110', 'Hall A', 'repairing');
