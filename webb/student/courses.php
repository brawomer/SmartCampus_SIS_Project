<?php
/**
 * Student Courses Page
 * View enrolled courses and course details
 */
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/helpers.php';

protectRouteWithRole('student');

$user = getCurrentUser();
$userId = $user['id'];

// Get student's enrolled courses
$enrollments = $pdo->findBy('enrollments', 'student_id', $userId);
$courses = [];

foreach ($enrollments as $enrollment) {
    $course = $pdo->find('courses', $enrollment['course_id']);
    if ($course) {
        $teacher = $pdo->find('users', $course['teacher_id']);
        $course['teacher_name'] = $teacher['full_name'] ?? 'TBA';
        $course['enrollment_status'] = $enrollment['status'] ?? 'active';
        $courses[] = $course;
    }
}

$pageTitle = "My Courses";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
        <p class="mt-2 text-gray-600"><?= CURRENT_SEMESTER ?></p>
    </div>

    <?php if (empty($courses)): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <i class="fas fa-book text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Courses Enrolled</h3>
            <p class="text-gray-600 mb-6">You haven't enrolled in any courses yet.</p>
            <a href="/student/browse-courses.php" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Browse Available Courses
            </a>
        </div>
    <?php else: ?>
        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                    <!-- Course Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold opacity-90"><?= htmlspecialchars($course['code']) ?></span>
                            <span class="px-2 py-1 bg-white bg-opacity-20 rounded text-xs"><?= $course['credits'] ?> Credits</span>
                        </div>
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($course['name']) ?></h3>
                    </div>
                    
                    <!-- Course Body -->
                    <div class="p-6">
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user-tie w-5 text-gray-400"></i>
                                <span class="ml-2"><?= htmlspecialchars($course['teacher_name']) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock w-5 text-gray-400"></i>
                                <span class="ml-2"><?= htmlspecialchars($course['schedule']) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-door-open w-5 text-gray-400"></i>
                                <span class="ml-2"><?= htmlspecialchars($course['room']) ?></span>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars($course['description']) ?></p>
                        
                        <a href="/student/course-details.php?id=<?= $course['id'] ?>" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
