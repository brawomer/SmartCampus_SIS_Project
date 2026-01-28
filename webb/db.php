<?php
/**
 * db.php - Central Database Connection
 * We use PDO (PHP Data Objects) because it is secure against SQL injection
 * when using prepared statements.
 */

$host = 'localhost';
$db   = 'smart_campus';
$user = 'root'; // Your DB username
$pass = '';     // Your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // In a real project, don't show the error message to the user
     die("Database connection failed: " . $e->getMessage());
}
?>