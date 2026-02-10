<?php
/**
 * Teacher Gradebook - Enter and manage grades
 */
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/helpers.php';

protectRouteWithRole('teacher');

$user = getCurrentUser();
$teacherId = $user['id'];

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_grade'])) {
    if (verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $gradeData = [
            'student_id' => (int)$_POST['student_id'],
            'course_id' => (int)$_POST['course_id'],
            'assignment_name' => sanitize($_POST['assignment_name']),
            'score' => (float)$_POST['score'],
            'weight' => (int)$_POST['weight'],
            'teacher_id' => $teacherId,
        ];
        $pdo->insert('grades', $gradeData);
        setFlash('success', 'Grade added successfully!');
        redirect($_SERVER['PHP_SELF'] . '?course_id=' . $gradeData['course_id']);
    }
}

// Get teacher's courses
$teacherCourses = $pdo->findBy('courses', 'teacher_id', $teacherId);
$selectedCourseId = $_GET['course_id'] ?? ($teacherCourses[0]['id'] ?? null);

$enrolledStudents = [];
if ($selectedCourseId) {
    $enrollments = $pdo->findBy('enrollments', 'course_id', $selectedCourseId);
    foreach ($enrollments as $enrollment) {
        $student = $pdo->find('users', $enrollment['student_id']);
        if ($student) {
            $enrolledStudents[] = $student;
        }
    }
}

$pageTitle = "Gradebook";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gradebook</h1>
        <p class="mt-2 text-gray-600">Enter and manage student grades</p>
    </div>

    <!-- Course Selector -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Select Course</label>
        <select onchange="window.location.href='?course_id='+this.value" class="block w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <option value="">-- Select a Course --</option>
            <?php foreach ($teacherCourses as $course): ?>
                <option value="<?= $course['id'] ?>" <?= $selectedCourseId == $course['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($course['code'] . ' - ' . $course['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($selectedCourseId && !empty($enrolledStudents)): ?>
        <!-- Add Grade Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Add New Grade</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="course_id" value="<?= $selectedCourseId ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                    <select name="student_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <?php foreach ($enrolledStudents as $student): ?>
                            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assignment</label>
                    <input type="text" name="assignment_name" required placeholder="Midterm Exam" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Score (%)</label>
                    <input type="number" name="score" required min="0" max="100" step="0.1" placeholder="85" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weight (%)</label>
                    <input type="number" name="weight" required min="1" max="100" placeholder="20" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" name="add_grade" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Add Grade
                    </button>
                </div>
            </form>
        </div>

        <!-- Grades Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php 
                        $courseGrades = $pdo->findBy('grades', 'course_id', $selectedCourseId);
                        usort($courseGrades, function($a, $b) {
                            return strtotime($b['created_at']) - strtotime($a['created_at']);
                        });
                        $courseGrades = array_slice($courseGrades, 0, 20);
                        
                        foreach ($courseGrades as $grade):
                            $student = $pdo->find('users', $grade['student_id']);
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($student['full_name'] ?? 'Unknown') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($grade['assignment_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-semibold <?= $grade['score'] >= 70 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $grade['score'] ?>%
                                    </span>
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
    <?php elseif ($selectedCourseId): ?>
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <p class="text-gray-600">No students enrolled in this course yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php 
$flash = getFlash();
if ($flash):
?>
<div id="flash-message" class="fixed bottom-4 right-4 px-6 py-3 bg-green-500 text-white rounded-lg shadow-lg">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<script>
setTimeout(() => {
    document.getElementById('flash-message')?.remove();
}, 3000);
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
