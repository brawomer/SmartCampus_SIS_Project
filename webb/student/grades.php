<?php
/**
 * Student Grades Page
 * View detailed grade breakdown and GPA
 */
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/helpers.php';

protectRouteWithRole('student');

$user = getCurrentUser();
$userId = $user['id'];

// Get student's grades
$grades = $pdo->findBy('grades', 'student_id', $userId);

// Group grades by course
$gradesByCourse = [];
foreach ($grades as $grade) {
    $courseId = $grade['course_id'];
    if (!isset($gradesByCourse[$courseId])) {
        $course = $pdo->find('courses', $courseId);
        $gradesByCourse[$courseId] = [
            'course' => $course,
            'grades' => [],
            'total_score' => 0,
            'total_weight' => 0,
        ];
    }
    $gradesByCourse[$courseId]['grades'][] = $grade;
    $gradesByCourse[$courseId]['total_score'] += ($grade['score'] * $grade['weight'] / 100);
    $gradesByCourse[$courseId]['total_weight'] += $grade['weight'];
}

// Calculate overall GPA
$gpaData = [];
foreach ($gradesByCourse as $data) {
    if ($data['total_weight'] > 0) {
        $finalScore = $data['total_score'];
        $gpaData[] = [
            'score' => $finalScore,
            'credits' => $data['course']['credits'] ?? 3
        ];
    }
}
$overallGPA = calculateGPA($gpaData);

$pageTitle = "My Grades";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page Header with GPA -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Grades</h1>
            <p class="mt-2 text-gray-600"><?= CURRENT_SEMESTER ?></p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-600">Overall GPA</div>
            <div class="text-4xl font-bold text-indigo-600"><?= number_format($overallGPA, 2) ?></div>
        </div>
    </div>

    <?php if (empty($gradesByCourse)): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Grades Posted</h3>
            <p class="text-gray-600">Your grades will appear here once your teachers post them.</p>
        </div>
    <?php else: ?>
        <!-- Grades by Course -->
        <div class="space-y-6">
            <?php foreach ($gradesByCourse as $data): ?>
                <?php 
                $course = $data['course'];
                $courseGrades = $data['grades'];
                $finalScore = $data['total_weight'] > 0 ? $data['total_score'] : 0;
                $letterGrade = scoreToLetterGrade($finalScore);
                ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Course Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($course['name']) ?></h3>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($course['code']) ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600"><?= $letterGrade ?></div>
                            <div class="text-sm text-gray-600"><?= number_format($finalScore, 1) ?>%</div>
                        </div>
                    </div>
                    
                    <!-- Grades Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($courseGrades as $grade): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($grade['assignment_name']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="font-semibold"><?= $grade['score'] ?>%</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?= $grade['weight'] ?>%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?= formatDate($grade['created_at']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
