import 'package:flutter/material.dart';

class DashboardScreen extends StatefulWidget {
  final String role; // This matches the "role" variable you are passing

  const DashboardScreen({super.key, required this.role});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _selectedIndex = 0;

  // Sample data for students
  final List<Map<String, String>> lectures = [
    {
      'title': 'Mathematics 101',
      'instructor': 'Prof. Ahmed',
      'time': '10:00 AM',
    },
    {
      'title': 'Physics Basics',
      'instructor': 'Prof. Sarah',
      'time': '01:00 PM',
    },
    {'title': 'Chemistry Lab', 'instructor': 'Prof. John', 'time': '03:00 PM'},
  ];

  final List<Map<String, String>> grades = [
    {'subject': 'Mathematics', 'grade': 'A', 'percentage': '92%'},
    {'subject': 'Physics', 'grade': 'B+', 'percentage': '88%'},
    {'subject': 'Chemistry', 'grade': 'A-', 'percentage': '90%'},
    {'subject': 'English', 'grade': 'B', 'percentage': '85%'},
  ];

  final List<Map<String, String>> attendance = [
    {'date': '2026-01-30', 'status': 'Present', 'color': 'green'},
    {'date': '2026-01-29', 'status': 'Present', 'color': 'green'},
    {'date': '2026-01-28', 'status': 'Absent', 'color': 'red'},
    {'date': '2026-01-27', 'status': 'Present', 'color': 'green'},
    {'date': '2026-01-26', 'status': 'Late', 'color': 'yellow'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.role} Dashboard'),
        backgroundColor: widget.role == 'Teacher' ? Colors.blue : Colors.green,
        elevation: 0,
      ),
      body: widget.role == 'Student'
          ? _buildStudentDashboard()
          : _buildTeacherDashboard(),
      bottomNavigationBar: widget.role == 'Student'
          ? BottomNavigationBar(
              currentIndex: _selectedIndex,
              onTap: (index) {
                setState(() {
                  _selectedIndex = index;
                });
              },
              items: const [
                BottomNavigationBarItem(
                  icon: Icon(Icons.book),
                  label: 'Lectures',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Icons.grade),
                  label: 'Grades',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Icons.event_available),
                  label: 'Attendance',
                ),
              ],
            )
          : null,
    );
  }

  Widget _buildStudentDashboard() {
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
            child: ListView.builder(
              itemCount: lectures.length,
              itemBuilder: (context, index) {
                final lecture = lectures[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  elevation: 2,
                  child: ListTile(
                    leading: const Icon(Icons.class_, color: Colors.blue),
                    title: Text(
                      lecture['title']!,
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 4),
                        Text('Instructor: ${lecture['instructor']}'),
                        Text('Time: ${lecture['time']}'),
                      ],
                    ),
                    trailing: const Icon(Icons.arrow_forward),
                  ),
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
            child: ListView.builder(
              itemCount: grades.length,
              itemBuilder: (context, index) {
                final grade = grades[index];
                Color gradeColor = _getGradeColor(grade['grade']!);
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  elevation: 2,
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: gradeColor,
                      child: Text(
                        grade['grade']!,
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    title: Text(
                      grade['subject']!,
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Text('Score: ${grade['percentage']}'),
                    trailing: Icon(Icons.check_circle, color: gradeColor),
                  ),
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
            child: ListView.builder(
              itemCount: attendance.length,
              itemBuilder: (context, index) {
                final record = attendance[index];
                Color statusColor = _getStatusColor(record['color']!);
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  elevation: 2,
                  child: ListTile(
                    leading: Icon(
                      _getStatusIcon(record['status']!),
                      color: statusColor,
                      size: 28,
                    ),
                    title: Text(
                      record['date']!,
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
                        record['status']!,
                        style: TextStyle(
                          color: statusColor,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
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

  Color _getStatusColor(String color) {
    switch (color) {
      case 'green':
        return Colors.green;
      case 'red':
        return Colors.red;
      case 'yellow':
        return Colors.orange;
      default:
        return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status) {
      case 'Present':
        return Icons.check_circle;
      case 'Absent':
        return Icons.cancel;
      case 'Late':
        return Icons.schedule;
      default:
        return Icons.help;
    }
  }
}
