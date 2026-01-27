import 'package:flutter/material.dart';

class DashboardScreen extends StatelessWidget {
  final String role; // This matches the "role" variable you are passing

  const DashboardScreen({super.key, required this.role});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('$role Dashboard'),
        backgroundColor: role == 'Teacher' ? Colors.blue : Colors.green,
      ),
      body: Center(
        child: Text(
          'Welcome to the SmartCampus $role Panel',
          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
        ),
      ),
    );
  }
}