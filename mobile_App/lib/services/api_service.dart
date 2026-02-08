import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // ============================================
  // CONFIGURATION - Update these values
  // ============================================

  // For Android Emulator: use 'http://10.0.2.2/your_api'
  // For iOS Simulator: use 'http://localhost/your_api'
  // For Real Device: use 'http://YOUR_PC_IP/your_api' (e.g., 192.168.1.x)
  // For Production: use 'https://your-domain.com/api'
  static const String baseUrl = 'http://10.37.249.1/webb/api';

  // Default headers for all requests
  static Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  // ============================================
  // AUTHENTICATION
  // ============================================

  /// Login with email and password
  /// Returns user data with role on success
  static Future<Map<String, dynamic>> login(
    String email,
    String password,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login.php'),
        headers: _headers,
        body: jsonEncode({'email': email, 'password': password}),
      );

      return _handleResponse(response);
    } catch (e) {
      throw ApiException('Connection error: $e');
    }
  }

  /// Register a new user
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    String role = 'Student',
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register.php'),
        headers: _headers,
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'role': role,
        }),
      );

      return _handleResponse(response);
    } catch (e) {
      throw ApiException('Connection error: $e');
    }
  }

  // ============================================
  // GENERIC HTTP METHODS
  // ============================================

  /// GET request
  static Future<dynamic> get(String endpoint) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/$endpoint'),
        headers: _headers,
      );

      return _handleResponse(response);
    } catch (e) {
      throw ApiException('Connection error: $e');
    }
  }

  /// POST request with body
  static Future<dynamic> post(
    String endpoint,
    Map<String, dynamic> body,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/$endpoint'),
        headers: _headers,
        body: jsonEncode(body),
      );

      return _handleResponse(response);
    } catch (e) {
      throw ApiException('Connection error: $e');
    }
  }

  /// PUT request for updates
  static Future<dynamic> put(String endpoint, Map<String, dynamic> body) async {
    try {
      final response = await http.put(
        Uri.parse('$baseUrl/$endpoint'),
        headers: _headers,
        body: jsonEncode(body),
      );

      return _handleResponse(response);
    } catch (e) {
      throw ApiException('Connection error: $e');
    }
  }

  /// DELETE request
  static Future<dynamic> delete(String endpoint) async {
    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/$endpoint'),
        headers: _headers,
      );

      return _handleResponse(response);
    } catch (e) {
      throw ApiException('Connection error: $e');
    }
  }

  // ============================================
  // STUDENT DASHBOARD METHODS (Instance Methods)
  // ============================================

  /// Get lectures for student dashboard
  Future<List<Map<String, dynamic>>> getLectures(int studentId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/lectures.php?student_id=$studentId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading lectures: $e');
    }
  }

  /// Get grades for student dashboard
  Future<List<Map<String, dynamic>>> getGrades(int studentId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/grades.php?student_id=$studentId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading grades: $e');
    }
  }

  /// Get attendance for student dashboard
  Future<List<Map<String, dynamic>>> getAttendance(int studentId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/attendance.php?student_id=$studentId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading attendance: $e');
    }
  }

  // ============================================
  // TEACHER DASHBOARD METHODS (Instance Methods)
  // ============================================

  /// Get all students list for teacher
  Future<List<Map<String, dynamic>>> getStudents() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/students.php'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading students: $e');
    }
  }

  /// Get teacher's lectures
  Future<List<Map<String, dynamic>>> getTeacherLectures() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/teacher_lectures.php'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading teacher lectures: $e');
    }
  }

  /// Get grades for a specific student
  Future<List<Map<String, dynamic>>> getStudentGrades(dynamic studentId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/student_grades.php?student_id=$studentId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading student grades: $e');
    }
  }

  /// Add a grade for a student
  Future<bool> addGrade(
    dynamic studentId,
    String subject,
    String grade,
    int percentage,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/add_grade.php'),
        headers: _headers,
        body: jsonEncode({
          'student_id': studentId,
          'subject': subject,
          'grade': grade,
          'percentage': percentage,
        }),
      );
      final data = _handleResponse(response);
      return data['success'] == true;
    } catch (e) {
      return false;
    }
  }

  /// Get attendance records for a specific student
  Future<List<Map<String, dynamic>>> getStudentAttendance(
    dynamic studentId,
  ) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/student_attendance.php?student_id=$studentId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading student attendance: $e');
    }
  }

  /// Mark attendance for a student
  Future<bool> markAttendance(
    dynamic studentId,
    String date,
    String status,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/mark_attendance.php'),
        headers: _headers,
        body: jsonEncode({
          'student_id': studentId,
          'date': date,
          'status': status,
        }),
      );
      final data = _handleResponse(response);
      return data['success'] == true;
    } catch (e) {
      return false;
    }
  }

  // ============================================
  // HOD DASHBOARD METHODS
  // ============================================

  /// Get courses for HOD's department
  Future<List<Map<String, dynamic>>> getHODCourses(int hodId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hod/courses.php?hod_id=$hodId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['courses'] is List) {
        return List<Map<String, dynamic>>.from(data['courses']);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading HOD courses: $e');
    }
  }

  /// Get assignments for HOD's department
  Future<List<Map<String, dynamic>>> getHODAssignments(int hodId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hod/assignments.php?hod_id=$hodId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['assignments'] is List) {
        return List<Map<String, dynamic>>.from(data['assignments']);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading HOD assignments: $e');
    }
  }

  /// Get attendance for HOD's department
  Future<List<Map<String, dynamic>>> getHODAttendance(int hodId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hod/attendance.php?hod_id=$hodId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['attendance_summary'] is List) {
        return List<Map<String, dynamic>>.from(data['attendance_summary']);
      }
      if (data['attendance'] is List) {
        return List<Map<String, dynamic>>.from(data['attendance']);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading HOD attendance: $e');
    }
  }

  /// Get schedule for HOD's department
  Future<List<Map<String, dynamic>>> getHODSchedule(int hodId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hod/schedule.php?hod_id=$hodId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['schedule'] is List) {
        return List<Map<String, dynamic>>.from(data['schedule']);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading HOD schedule: $e');
    }
  }

  // ============================================
  // TECHNICIAN DASHBOARD METHODS
  // ============================================

  /// Get maintenance requests for technician
  Future<List<Map<String, dynamic>>> getMaintenanceRequests(
    int technicianId,
  ) async {
    try {
      final response = await http.get(
        Uri.parse(
          '$baseUrl/technician/maintenance_requests.php?technician_id=$technicianId',
        ),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['maintenance_requests'] is List) {
        return List<Map<String, dynamic>>.from(data['maintenance_requests']);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading maintenance requests: $e');
    }
  }

  /// Mark item as fixed
  Future<bool> fixItem(dynamic itemId, String notes) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/technician/fix_item.php'),
        headers: _headers,
        body: jsonEncode({'item_id': itemId, 'notes': notes}),
      );
      final data = _handleResponse(response);
      return data['success'] == true;
    } catch (e) {
      return false;
    }
  }

  /// Create a new purchase request
  Future<bool> createPurchaseRequest(
    int technicianId,
    String itemName,
    int quantity,
    String reason,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/technician/purchase_request.php'),
        headers: _headers,
        body: jsonEncode({
          'technician_id': technicianId,
          'item_name': itemName,
          'quantity': quantity,
          'reason': reason,
        }),
      );
      final data = _handleResponse(response);
      return data['success'] == true;
    } catch (e) {
      return false;
    }
  }

  // ============================================
  // COMMON METHODS (for all roles)
  // ============================================

  /// Get notifications for user
  Future<List<Map<String, dynamic>>> getNotifications(int userId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/common/notifications.php?user_id=$userId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      if (data is List) {
        return List<Map<String, dynamic>>.from(data);
      }
      if (data['notifications'] is List) {
        return List<Map<String, dynamic>>.from(data['notifications']);
      }
      if (data['data'] is List) {
        return List<Map<String, dynamic>>.from(data['data']);
      }
      return [];
    } catch (e) {
      throw ApiException('Error loading notifications: $e');
    }
  }

  /// Get unread notification count
  Future<int> getUnreadCount(int userId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/common/unread_count.php?user_id=$userId'),
        headers: _headers,
      );
      final data = _handleResponse(response);
      return data['count'] ?? 0;
    } catch (e) {
      return 0;
    }
  }

  // ============================================
  // RESPONSE HANDLER
  // ============================================

  static dynamic _handleResponse(http.Response response) {
    final body = jsonDecode(response.body);

    switch (response.statusCode) {
      case 200:
      case 201:
        return body;
      case 400:
        throw ApiException(body['message'] ?? 'Bad request');
      case 401:
        throw ApiException(body['message'] ?? 'Unauthorized');
      case 404:
        throw ApiException(body['message'] ?? 'Not found');
      case 500:
        throw ApiException(body['message'] ?? 'Server error');
      default:
        throw ApiException('Error: ${response.statusCode}');
    }
  }
}

/// Custom exception for API errors
class ApiException implements Exception {
  final String message;
  ApiException(this.message);

  @override
  String toString() => message;
}
