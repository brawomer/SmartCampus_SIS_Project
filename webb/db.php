<?php
/**
 * db.php - Enhanced File-based Database with Multiple Collections
 * Supports users, courses, grades, attendance, messages, and more
 */

require_once __DIR__ . '/config.php';

$data_dir = DATA_PATH;

// Initialize all data files
$dataFiles = [
    'users' => $data_dir . '/users.json',
    'courses' => $data_dir . '/courses.json',
    'enrollments' => $data_dir . '/enrollments.json',
    'grades' => $data_dir . '/grades.json',
    'attendance' => $data_dir . '/attendance.json',
    'assignments' => $data_dir . '/assignments.json',
    'submissions' => $data_dir . '/submissions.json',
    'messages' => $data_dir . '/messages.json',
    'notifications' => $data_dir . '/notifications.json',
    'announcements' => $data_dir . '/announcements.json',
    'departments' => $data_dir . '/departments.json',
];

// Initialize users if not exists
if (!file_exists($dataFiles['users'])) {
    $password_hash = password_hash('password123', PASSWORD_DEFAULT);
    
    $initial_users = [
        [
            'id' => 1,
            'username' => 'admin_user',
            'password' => $password_hash,
            'email' => 'admin@campus.com',
            'role' => 'admin',
            'full_name' => 'System Administrator',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'username' => 'tech_mike',
            'password' => $password_hash,
            'email' => 'mike@tech.com',
            'role' => 'technician',
            'full_name' => 'Mike Repairman',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 3,
            'username' => 'teacher_jane',
            'password' => $password_hash,
            'email' => 'jane@campus.com',
            'role' => 'teacher',
            'full_name' => 'Jane Doe',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 4,
            'username' => 'parent_smith',
            'password' => $password_hash,
            'email' => 'smith@parent.com',
            'role' => 'parent',
            'full_name' => 'John Smith',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 5,
            'username' => 'student_alex',
            'password' => $password_hash,
            'email' => 'alex@student.com',
            'role' => 'student',
            'full_name' => 'Alex Thompson',
            'student_id' => 'SC-2026-001',
            'department' => 'Computer Science',
            'year' => 3,
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    file_put_contents($dataFiles['users'], json_encode($initial_users, JSON_PRETTY_PRINT));
}

// Initialize sample courses
if (!file_exists($dataFiles['courses'])) {
    $sampleCourses = [
        [
            'id' => 1,
            'code' => 'CS101',
            'name' => 'Introduction to Programming',
            'description' => 'Learn the fundamentals of programming using Python',
            'credits' => 3,
            'department' => 'Computer Science',
            'teacher_id' => 3,
            'semester' => CURRENT_SEMESTER,
            'schedule' => 'Mon/Wed 10:00-11:30 AM',
            'room' => 'Lab 202',
            'capacity' => 30,
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'code' => 'CS201',
            'name' => 'Data Structures',
            'description' => 'Advanced data structures and algorithms',
            'credits' => 4,
            'department' => 'Computer Science',
            'teacher_id' => 3,
            'semester' => CURRENT_SEMESTER,
            'schedule' => 'Tue/Thu 2:00-3:30 PM',
            'room' => 'Lab 301',
            'capacity' => 25,
            'created_at' => date('Y-m-d H:i:s')
        ],
    ];
    file_put_contents($dataFiles['courses'], json_encode($sampleCourses, JSON_PRETTY_PRINT));
}

// Initialize other files with empty arrays
foreach (['enrollments', 'grades', 'attendance', 'assignments', 'submissions', 'messages', 'notifications', 'announcements', 'departments'] as $collection) {
    if (!file_exists($dataFiles[$collection])) {
        file_put_contents($dataFiles[$collection], json_encode([], JSON_PRETTY_PRINT));
    }
}

// Enhanced FileDB class
class FileDB {
    private $dataFiles;
    
    public function __construct($dataFiles) {
        $this->dataFiles = $dataFiles;
    }
    
    // Generic methods for all collections
    public function getAll($collection) {
        if (!isset($this->dataFiles[$collection])) {
            return [];
        }
        $file = $this->dataFiles[$collection];
        if (!file_exists($file)) {
            return [];
        }
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }
    
    public function save($collection, $data) {
        if (!isset($this->dataFiles[$collection])) {
            return false;
        }
        return file_put_contents($this->dataFiles[$collection], json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }
    
    public function find($collection, $id) {
        $items = $this->getAll($collection);
        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }
    
    public function findBy($collection, $field, $value) {
        $items = $this->getAll($collection);
        $results = [];
        foreach ($items as $item) {
            if (isset($item[$field]) && $item[$field] == $value) {
                $results[] = $item;
            }
        }
        return $results;
    }
    
    public function insert($collection, $data) {
        $items = $this->getAll($collection);
        $data['id'] = $this->getNextId($collection);
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $items[] = $data;
        $this->save($collection, $items);
        return $data['id'];
    }
    
    public function update($collection, $id, $data) {
        $items = $this->getAll($collection);
        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                $items[$key] = array_merge($item, $data);
                $items[$key]['updated_at'] = date('Y-m-d H:i:s');
                return $this->save($collection, $items);
            }
        }
        return false;
    }
    
    public function delete($collection, $id) {
        $items = $this->getAll($collection);
        $items = array_filter($items, function($item) use ($id) {
            return $item['id'] != $id;
        });
        return $this->save($collection, array_values($items));
    }
    
    private function getNextId($collection) {
        $items = $this->getAll($collection);
        if (empty($items)) {
            return 1;
        }
        $maxId = max(array_column($items, 'id'));
        return $maxId + 1;
    }
    
    // Legacy PDO-like interface for backward compatibility
    public function prepare($sql) {
        return new FileDBStatement($this, $sql);
    }
    
    public function query($sql) {
        $stmt = new FileDBStatement($this, $sql);
        $stmt->execute([]);
        return $stmt;
    }
}

// Legacy statement class for backward compatibility
class FileDBStatement {
    private $db;
    private $sql;
    private $result;
    
    public function __construct($db, $sql) {
        $this->db = $db;
        $this->sql = $sql;
    }
    
    public function execute($params) {
        $users = $this->db->getAll('users');
        
        if (stripos($this->sql, 'SELECT') === 0) {
            if (preg_match('/WHERE username = \?/i', $this->sql) && !empty($params)) {
                $username = $params[0];
                foreach ($users as $user) {
                    if ($user['username'] === $username) {
                        $this->result = [$user];
                        return true;
                    }
                }
                $this->result = [];
            } else {
                $this->result = $users;
            }
            return true;
        }
        
        if (stripos($this->sql, 'INSERT') === 0) {
            $new_user = [
                'username' => $params[0] ?? '',
                'password' => $params[1] ?? '',
                'email' => $params[2] ?? '',
                'role' => $params[3] ?? '',
                'full_name' => $params[4] ?? ''
            ];
            $this->db->insert('users', $new_user);
            return true;
        }
        
        return false;
    }
    
    public function fetch() {
        if (!empty($this->result)) {
            return array_shift($this->result);
        }
        return false;
    }
    
    public function fetchAll() {
        return $this->result ?: [];
    }
    
    public function fetchColumn() {
        if (!empty($this->result)) {
            $row = array_shift($this->result);
            return reset($row);
        }
        return false;
    }
    
    public function rowCount() {
        return count($this->result ?: []);
    }
}

// Create global $pdo object
$pdo = new FileDB($dataFiles);
?>