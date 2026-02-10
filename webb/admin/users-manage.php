<?php
/**
 * Admin User Management - CRUD operations for all users
 */
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/helpers.php';

protectRouteWithRole('admin');

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    if (verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $userData = [
            'username' => sanitize($_POST['username']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'email' => sanitize($_POST['email']),
            'role' => sanitize($_POST['role']),
            'full_name' => sanitize($_POST['full_name']),
        ];
        $pdo->insert('users', $userData);
        setFlash('success', 'User created successfully!');
        redirect($_SERVER['PHP_SELF']);
    }
}

// Handle user deletion
if (isset($_GET['delete']) && isset($_GET['token'])) {
    if (verifyCsrfToken($_GET['token'])) {
        $pdo->delete('users', (int)$_GET['delete']);
        setFlash('success', 'User deleted successfully!');
        redirect($_SERVER['PHP_SELF']);
    }
}

// Get all users
$users = $pdo->getAll('users');
$roleFilter = $_GET['role'] ?? 'all';

if ($roleFilter !== 'all') {
    $users = array_filter($users, function($user) use ($roleFilter) {
        return $user['role'] === $roleFilter;
    });
}

$pageTitle = "User Management";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <p class="mt-2 text-gray-600">Manage all system users</p>
        </div>
        <button onclick="document.getElementById('createUserModal').classList.remove('hidden')" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i>Create User
        </button>
    </div>

    <!-- Role Filter -->
    <div class="mb-6 flex gap-2">
        <a href="?role=all" class="px-4 py-2 rounded-lg <?= $roleFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?>">All</a>
        <a href="?role=student" class="px-4 py-2 rounded-lg <?= $roleFilter === 'student' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?>">Students</a>
        <a href="?role=teacher" class="px-4 py-2 rounded-lg <?= $roleFilter === 'teacher' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?>">Teachers</a>
        <a href="?role=admin" class="px-4 py-2 rounded-lg <?= $roleFilter === 'admin' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?>">Admins</a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($user['full_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : '' ?>
                                <?= $user['role'] === 'teacher' ? 'bg-blue-100 text-blue-800' : '' ?>
                                <?= $user['role'] === 'student' ? 'bg-green-100 text-green-800' : '' ?>
                                <?= !in_array($user['role'], ['admin', 'teacher', 'student']) ? 'bg-gray-100 text-gray-800' : '' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= formatDate($user['created_at'] ?? '') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="?delete=<?= $user['id'] ?>&token=<?= generateCsrfToken() ?>" 
                               onclick="return confirm('Are you sure you want to delete this user?')"
                               class="text-red-600 hover:text-red-900">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Create New User</h2>
            <button onclick="document.getElementById('createUserModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required minlength="8" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                    <option value="technician">Technician</option>
                    <option value="parent">Parent</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            
            <button type="submit" name="create_user" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Create User
            </button>
        </form>
    </div>
</div>

<?php 
$flash = getFlash();
if ($flash):
?>
<div id="flash-message" class="fixed bottom-4 right-4 px-6 py-3 bg-green-500 text-white rounded-lg shadow-lg">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<script>
setTimeout(() => document.getElementById('flash-message')?.remove(), 3000);
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
