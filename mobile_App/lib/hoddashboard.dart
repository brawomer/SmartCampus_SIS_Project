import 'package:flutter/material.dart';
import 'services/api_service.dart';

class HODDashboard extends StatefulWidget {
  final int userId;
  final String userName;

  const HODDashboard({super.key, required this.userId, required this.userName});

  @override
  State<HODDashboard> createState() => _HODDashboardState();
}

class _HODDashboardState extends State<HODDashboard> {
  int _selectedIndex = 0;
  final ApiService _apiService = ApiService();

  late Future<List<Map<String, dynamic>>> coursesFuture;
  late Future<List<Map<String, dynamic>>> assignmentsFuture;
  late Future<List<Map<String, dynamic>>> attendanceFuture;
  late Future<List<Map<String, dynamic>>> scheduleFuture;

  @override
  void initState() {
    super.initState();
    coursesFuture = _apiService.getHODCourses(widget.userId);
    assignmentsFuture = _apiService.getHODAssignments(widget.userId);
    attendanceFuture = _apiService.getHODAttendance(widget.userId);
    scheduleFuture = _apiService.getHODSchedule(widget.userId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('HOD Dashboard'),
        backgroundColor: Colors.purple,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            tooltip: 'Logout',
            onPressed: () => _logout(context),
          ),
        ],
      ),
      body: IndexedStack(
        index: _selectedIndex,
        children: [
          _buildCoursesView(),
          _buildAssignmentsView(),
          _buildAttendanceView(),
          _buildScheduleView(),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() {
            _selectedIndex = index;
          });
        },
        type: BottomNavigationBarType.fixed,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.book), label: 'Courses'),
          BottomNavigationBarItem(
            icon: Icon(Icons.assignment),
            label: 'Assignments',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.event_available),
            label: 'Attendance',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.schedule),
            label: 'Schedule',
          ),
        ],
      ),
    );
  }

  Widget _buildCoursesView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Department Courses',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: coursesFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No courses found'));
                }

                final courses = snapshot.data!;
                return ListView.builder(
                  itemCount: courses.length,
                  itemBuilder: (context, index) {
                    final course = courses[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: const Icon(Icons.book, color: Colors.purple),
                        title: Text(
                          course['name'] ?? 'Unknown Course',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Text(
                          'Teacher: ${course['teacher_name'] ?? 'N/A'}',
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

  Widget _buildAssignmentsView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Department Assignments',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: assignmentsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No assignments found'));
                }

                final assignments = snapshot.data!;
                return ListView.builder(
                  itemCount: assignments.length,
                  itemBuilder: (context, index) {
                    final assignment = assignments[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: const Icon(
                          Icons.assignment,
                          color: Colors.orange,
                        ),
                        title: Text(
                          assignment['title'] ?? 'Unknown Assignment',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Course: ${assignment['course_name'] ?? 'N/A'}',
                            ),
                            Text('Due: ${assignment['due_date'] ?? 'N/A'}'),
                          ],
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

  Widget _buildAttendanceView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Department Attendance',
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
                  return const Center(child: Text('No attendance data found'));
                }

                final attendance = snapshot.data!;
                return ListView.builder(
                  itemCount: attendance.length,
                  itemBuilder: (context, index) {
                    final record = attendance[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: const Icon(
                          Icons.event_available,
                          color: Colors.green,
                        ),
                        title: Text(
                          record['course_name'] ?? 'Unknown Course',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Text(
                          'Attendance Rate: ${record['attendance_rate'] ?? 'N/A'}',
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

  Widget _buildScheduleView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Department Schedule',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: scheduleFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No schedule found'));
                }

                final schedule = snapshot.data!;
                return ListView.builder(
                  itemCount: schedule.length,
                  itemBuilder: (context, index) {
                    final item = schedule[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: const Icon(Icons.schedule, color: Colors.blue),
                        title: Text(
                          item['course_name'] ?? 'Unknown Course',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Day: ${item['day'] ?? 'N/A'}'),
                            Text('Time: ${item['time'] ?? 'N/A'}'),
                            Text('Room: ${item['room'] ?? 'N/A'}'),
                          ],
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

  void _logout(BuildContext context) {
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
                Navigator.pop(context);
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
