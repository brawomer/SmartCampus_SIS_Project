<?php
/**
 * login.php - Handles user authentication and role assignment
 */
require 'db.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Fetch user from database
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 2. Verify password (Assuming you used password_hash() to store it)
    if ($user && password_verify($password, $user['password'])) {
        // 3. Store user data in Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // e.g., 'admin', 'technician', 'student'
        if ($user['role'] === 'super-admin') {
            $_SESSION['is_super_admin'] = true;
            header("Location: admin/dashboard.php");
            exit();
        } elseif ($user['role'] === 'teacher') {
            header("Location: head/dashboard.php");
        exit();
        } elseif ($user['role'] === 'technician') {
            header("Location: technician/dashboard.php");
            exit();
            } elseif ($user['role'] === 'marketing') {
                header("Location: marketing/dashboard.php");
                exit();
            } elseif ($user['role'] === 'staff') {
                header("Location: staff/dashboard.php");
                exit();
            } elseif ($user['role'] === 'student') {
                header("Location: student/dashboard.php");
                exit();
            } else {
                $error = "Unknown user role.";
        }

    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SmartCampus | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">SmartCampus Login</h2>
        
        <?php if($error): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm font-medium">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Username</label>
                <input type="text" name="username" required class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Password</label>
                <input type="password" name="password" required class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>