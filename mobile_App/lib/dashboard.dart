import 'package:flutter/material.dart';
import 'services/api_service.dart';
import 'teacherdashboard.dart';
import 'hoddashboard.dart';
import 'techniciandashboard.dart';
import 'staffdashboard.dart';

class DashboardScreen extends StatefulWidget {
  final String role;
  final int userId;
  final String userName;

  const DashboardScreen({
    super.key,
    required this.role,
    required this.userId,
    required this.userName,
  });

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _selectedIndex = 0;
  final ApiService _apiService = ApiService();

  late Future<List<Map<String, dynamic>>> lecturesFuture;
  late Future<List<Map<String, dynamic>>> gradesFuture;
  late Future<List<Map<String, dynamic>>> attendanceFuture;

  @override
  void initState() {
    super.initState();
    // Load data from API on init with student ID
    lecturesFuture = _apiService.getLectures(widget.userId);
    gradesFuture = _apiService.getGrades(widget.userId);
    attendanceFuture = _apiService.getAttendance(widget.userId);
  }

  @override
  Widget build(BuildContext context) {
    // Route to appropriate dashboard based on role
    switch (widget.role) {
      case 'HOD':
        return HODDashboard(userId: widget.userId, userName: widget.userName);
      case 'Technician':
        return TechnicianDashboard(
          userId: widget.userId,
          userName: widget.userName,
        );
      case 'Staff':
        return StaffDashboard(userId: widget.userId, userName: widget.userName);
      case 'Teacher':
        return TeacherDashboard(
          userId: widget.userId,
          userName: widget.userName,
        );
      default:
        // Student dashboard (default)
        return _buildStudentDashboard();
    }
  }

  Widget _buildStudentDashboard() {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.role} Dashboard'),
        backgroundColor: Colors.green,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            tooltip: 'Logout',
            onPressed: () => _logout(context),
          ),
        ],
      ),
      body: _buildStudentDashboardContent(),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() {
            _selectedIndex = index;
          });
        },
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.book), label: 'Lectures'),
          BottomNavigationBarItem(icon: Icon(Icons.grade), label: 'Grades'),
          BottomNavigationBarItem(
            icon: Icon(Icons.event_available),
            label: 'Attendance',
          ),
        ],
      ),
    );
  }

  Widget _buildStudentDashboardContent() {
    switch (_selectedIndex) {
      case 0:
        return _buildLecturesView();
      case 1:
        return _buildGradesView();
      case 2:
        return _buildAttendanceView();
      default:
        return _buildLecturesView();
    }
  }

  Widget _buildLecturesView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Upcoming Lectures',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: lecturesFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No lectures found'));
                }

                final lectures = snapshot.data!;
                return ListView.builder(
                  itemCount: lectures.length,
                  itemBuilder: (context, index) {
                    final lecture = lectures[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: const Icon(Icons.class_, color: Colors.blue),
                        title: Text(
                          lecture['title'] ?? 'No Title',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const SizedBox(height: 4),
                            Text(
                              'Instructor: ${lecture['instructor'] ?? 'N/A'}',
                            ),
                            Text('Time: ${lecture['time'] ?? 'N/A'}'),
                          ],
                        ),
                        trailing: const Icon(Icons.arrow_forward),
                      ),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildGradesView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Your Grades',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: gradesFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No grades found'));
                }

                final grades = snapshot.data!;
                return ListView.builder(
                  itemCount: grades.length,
                  itemBuilder: (context, index) {
                    final grade = grades[index];
                    Color gradeColor = _getGradeColor(grade['grade'] ?? 'N');
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: CircleAvatar(
                          backgroundColor: gradeColor,
                          child: Text(
                            '${grade['grade'] ?? 'N'}',
                            style: const TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                        title: Text(
                          grade['subject'] ?? 'Unknown',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Text('Score: ${grade['percentage'] ?? 0}%'),
                        trailing: Icon(Icons.check_circle, color: gradeColor),
                      ),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAttendanceView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Attendance Record',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: attendanceFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(
                    child: Text('No attendance records found'),
                  );
                }

                final attendance = snapshot.data!;
                return ListView.builder(
                  itemCount: attendance.length,
                  itemBuilder: (context, index) {
                    final record = attendance[index];
                    String status = record['status'] ?? 'Unknown';
                    Color statusColor = _getStatusColor(status);
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: Icon(
                          _getStatusIcon(status),
                          color: statusColor,
                          size: 28,
                        ),
                        title: Text(
                          record['date'] ?? 'Unknown',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        trailing: Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 12,
                            vertical: 6,
                          ),
                          decoration: BoxDecoration(
                            color: statusColor.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Text(
                            status,
                            style: TextStyle(
                              color: statusColor,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTeacherDashboard() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(Icons.school, size: 80, color: Colors.blue),
          const SizedBox(height: 16),
          Text(
            'Welcome ${widget.role} Panel',
            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 16),
          const Text('Teacher features coming soon!'),
        ],
      ),
    );
  }

  Color _getGradeColor(String grade) {
    if (grade.startsWith('A')) return Colors.green;
    if (grade.startsWith('B')) return Colors.blue;
    if (grade.startsWith('C')) return Colors.orange;
    return Colors.red;
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'present':
        return Colors.green;
      case 'absent':
        return Colors.red;
      case 'late':
        return Colors.orange;
      default:
        return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status.toLowerCase()) {
      case 'present':
        return Icons.check_circle;
      case 'absent':
        return Icons.cancel;
      case 'late':
        return Icons.schedule;
      default:
        return Icons.help;
    }
  }

  void _logout(BuildContext context) {
    // Show confirmation dialog
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Logout'),
          content: const Text('Are you sure you want to logout?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel'),
            ),
            TextButton(
              onPressed: () {
                Navigator.pop(context); // Close dialog
                Navigator.pushReplacementNamed(context, '/');
              },
              child: const Text('Logout', style: TextStyle(color: Colors.red)),
            ),
          ],
        );
      },
    );
  }
}
