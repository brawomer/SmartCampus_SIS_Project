<?php
/**
 * register_student.php - Admin tool to register new students
 */
require '../db.php';
session_start();

// Security: Only Admins should be able to see this page
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super-admin')) {
    die("Unauthorized access. Only administrators can register students.");
}

$message = "";
$messageType = ""; // 'success' or 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and Sanitize Inputs
    $full_name   = $_POST['full_name'];
    $email       = $_POST['email'];
    $username    = $_POST['username'];
    $password    = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash it!
    $student_id  = $_POST['student_id_number'];
    $department  = $_POST['department'];
    $current_year = $_POST['current_year'];

    try {
        // START TRANSACTION: This ensures both tables update together
        $pdo->beginTransaction();

        // 2. Insert into 'users' table
        $sqlUser = "INSERT INTO users (username, password, email, role, full_name) VALUES (?, ?, ?, 'student', ?)";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([$username, $password, $email, $full_name]);
        
        // Get the ID of the user we just created
        $newUserId = $pdo->lastInsertId();

        // 3. Insert into 'students' table
        $sqlStudent = "INSERT INTO students (user_id, student_id_number, department, current_year) VALUES (?, ?, ?, ?)";
        $stmtStudent = $pdo->prepare($sqlStudent);
        $stmtStudent->execute([$newUserId, $student_id, $department, $current_year]);

        // COMMIT: Save everything to the database
        $pdo->commit();
        
        $message = "Student <b>$full_name</b> successfully registered!";
        $messageType = "success";

    } catch (Exception $e) {
        // ROLLBACK: If anything went wrong, undo everything
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SmartCampus | Register Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 min-h-screen p-8">

    <div class="max-w-2xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <a href="dashboard.php" class="text-blue-600 font-bold text-sm hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
            <h1 class="text-2xl font-black text-slate-800">Register New Student</h1>
        </div>

        <?php if($message): ?>
            <div class="mb-6 p-4 rounded-xl flex items-center gap-3 <?= $messageType === 'success' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                <p class="text-sm font-bold"><?= $message ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account Info -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-widest border-b pb-2">Account Details</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Full Name</label>
                        <input type="text" name="full_name" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Username</label>
                        <input type="text" name="username" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Initial Password</label>
                        <input type="password" name="password" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-widest border-b pb-2">Academic Profile</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Student ID (Unique)</label>
                        <input type="text" name="student_id_number" placeholder="e.g. SC-2026-001" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Department</label>
                        <select name="department" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="Computer Science">Computer Science</option>
                            <option value="Business Administration">Business Administration</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Visual Arts">Visual Arts</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Current Year</label>
                        <select name="current_year" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="1">Year 1</option>
                            <option value="2">Year 2</option>
                            <option value="3">Year 3</option>
                            <option value="4">Year 4</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i> Finalize Registration
                </button>
            </div>
        </form>
    </div>

</body>
</html>