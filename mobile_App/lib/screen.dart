import 'package:flutter/material.dart';

class DashboardScreen extends StatelessWidget {
  final String role;
  const DashboardScreen({super.key, required this.role});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('$role Dashboard'), centerTitle: true),
      body: GridView.count(
        padding: const EdgeInsets.all(20),
        crossAxisCount: 2,
        children: [
          _card(Icons.calendar_month, "Attendance"),
          _card(Icons.quiz, role == "Teacher" ? "Grade Quiz" : "My Grades"),
          _card(Icons.book, "Courses"),
          _card(Icons.person, "Profile"),
        ],
      ),
    );
  }

  Widget _card(IconData icon, String label) {
    return Card(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [Icon(icon, size: 40), Text(label)],
      ),
    );
  }
}
