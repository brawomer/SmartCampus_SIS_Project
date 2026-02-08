import 'package:flutter/material.dart';
import 'services/api_service.dart';

class StaffDashboard extends StatefulWidget {
  final int userId;
  final String userName;

  const StaffDashboard({
    super.key,
    required this.userId,
    required this.userName,
  });

  @override
  State<StaffDashboard> createState() => _StaffDashboardState();
}

class _StaffDashboardState extends State<StaffDashboard> {
  final ApiService _apiService = ApiService();
  late Future<List<Map<String, dynamic>>> notificationsFuture;

  @override
  void initState() {
    super.initState();
    notificationsFuture = _apiService.getNotifications(widget.userId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Staff Dashboard'),
        backgroundColor: Colors.indigo,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            tooltip: 'Logout',
            onPressed: () => _logout(context),
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildWelcomeCard(),
              const SizedBox(height: 24),
              _buildNotificationsSection(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildWelcomeCard() {
    return Card(
      elevation: 4,
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [Colors.indigo, Colors.indigo.shade700],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Icon(Icons.person, size: 48, color: Colors.white),
            const SizedBox(height: 12),
            Text(
              'Welcome, ${widget.userName}!',
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              'Staff Member',
              style: TextStyle(fontSize: 16, color: Colors.white70),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNotificationsSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Notifications',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 16),
        FutureBuilder<List<Map<String, dynamic>>>(
          future: notificationsFuture,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(
                child: Padding(
                  padding: EdgeInsets.all(32.0),
                  child: CircularProgressIndicator(),
                ),
              );
            }
            if (snapshot.hasError) {
              return Card(
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Text('Error: ${snapshot.error}'),
                ),
              );
            }
            if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return Card(
                child: Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(32.0),
                  child: Column(
                    children: const [
                      Icon(
                        Icons.notifications_off,
                        size: 48,
                        color: Colors.grey,
                      ),
                      SizedBox(height: 16),
                      Text(
                        'No notifications',
                        style: TextStyle(color: Colors.grey),
                      ),
                    ],
                  ),
                ),
              );
            }

            final notifications = snapshot.data!;
            return ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: notifications.length,
              itemBuilder: (context, index) {
                final notification = notifications[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  elevation: 2,
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: Colors.indigo.shade100,
                      child: Icon(
                        _getNotificationIcon(notification['type']),
                        color: Colors.indigo,
                      ),
                    ),
                    title: Text(
                      notification['title'] ?? 'Notification',
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 4),
                        Text(notification['message'] ?? ''),
                        const SizedBox(height: 4),
                        Text(
                          notification['date'] ?? '',
                          style: const TextStyle(
                            fontSize: 12,
                            color: Colors.grey,
                          ),
                        ),
                      ],
                    ),
                    isThreeLine: true,
                  ),
                );
              },
            );
          },
        ),
      ],
    );
  }

  IconData _getNotificationIcon(String? type) {
    switch (type?.toLowerCase()) {
      case 'announcement':
        return Icons.campaign;
      case 'reminder':
        return Icons.alarm;
      case 'update':
        return Icons.update;
      default:
        return Icons.notifications;
    }
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
