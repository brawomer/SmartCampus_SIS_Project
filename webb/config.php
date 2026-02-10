<?php
/**
 * config.php - Application Configuration
 * Central configuration file for SmartCampus
 */

// Application Settings
define('APP_NAME', 'SmartCampus');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'development'); // development, staging, production

// Paths
define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('LOGS_PATH', BASE_PATH . '/logs');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'smartcampus_session');

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes

// Pagination
define('ITEMS_PER_PAGE', 20);

// File Upload
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip']);

// Grading System
define('GRADING_SCALE', [
    'A+' => ['min' => 97, 'max' => 100, 'points' => 4.0],
    'A'  => ['min' => 93, 'max' => 96, 'points' => 4.0],
    'A-' => ['min' => 90, 'max' => 92, 'points' => 3.7],
    'B+' => ['min' => 87, 'max' => 89, 'points' => 3.3],
    'B'  => ['min' => 83, 'max' => 86, 'points' => 3.0],
    'B-' => ['min' => 80, 'max' => 82, 'points' => 2.7],
    'C+' => ['min' => 77, 'max' => 79, 'points' => 2.3],
    'C'  => ['min' => 73, 'max' => 76, 'points' => 2.0],
    'C-' => ['min' => 70, 'max' => 72, 'points' => 1.7],
    'D'  => ['min' => 60, 'max' => 69, 'points' => 1.0],
    'F'  => ['min' => 0, 'max' => 59, 'points' => 0.0],
]);

// Academic Calendar
define('CURRENT_SEMESTER', 'Spring 2026');
define('SEMESTER_START', '2026-01-15');
define('SEMESTER_END', '2026-05-15');

// Notification Settings
define('ENABLE_EMAIL_NOTIFICATIONS', false); // Set to true when email is configured
define('ENABLE_SMS_NOTIFICATIONS', false);

// Feature Flags
define('ENABLE_API', true);
define('ENABLE_MESSAGING', true);
define('ENABLE_PAYMENTS', true);
define('ENABLE_ANALYTICS', true);

// Time Zone
date_default_timezone_set('America/New_York');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', LOGS_PATH . '/error.log');
}

// Create necessary directories
$directories = [DATA_PATH, UPLOAD_PATH, LOGS_PATH];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
?>
