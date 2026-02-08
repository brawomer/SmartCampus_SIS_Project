import 'package:flutter/material.dart';
import 'services/api_service.dart';

class TechnicianDashboard extends StatefulWidget {
  final int userId;
  final String userName;

  const TechnicianDashboard({
    super.key,
    required this.userId,
    required this.userName,
  });

  @override
  State<TechnicianDashboard> createState() => _TechnicianDashboardState();
}

class _TechnicianDashboardState extends State<TechnicianDashboard> {
  int _selectedIndex = 0;
  final ApiService _apiService = ApiService();

  late Future<List<Map<String, dynamic>>> requestsFuture;

  @override
  void initState() {
    super.initState();
    requestsFuture = _apiService.getMaintenanceRequests(widget.userId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Technician Dashboard'),
        backgroundColor: Colors.teal,
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
        children: [_buildRequestsView(), _buildPurchasesView()],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() {
            _selectedIndex = index;
          });
        },
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.build), label: 'Requests'),
          BottomNavigationBarItem(
            icon: Icon(Icons.add_shopping_cart),
            label: 'New Request',
          ),
        ],
      ),
    );
  }

  Widget _buildRequestsView() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Maintenance Requests',
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: FutureBuilder<List<Map<String, dynamic>>>(
              future: requestsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No requests found'));
                }

                final requests = snapshot.data!;
                return ListView.builder(
                  itemCount: requests.length,
                  itemBuilder: (context, index) {
                    final request = requests[index];
                    String status = request['status'] ?? 'pending';
                    Color statusColor = _getStatusColor(status);

                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      elevation: 2,
                      child: ListTile(
                        leading: Icon(
                          status == 'fixed' ? Icons.check_circle : Icons.build,
                          color: statusColor,
                        ),
                        title: Text(
                          request['name'] ?? 'Unknown Item',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Issue: ${request['description'] ?? 'N/A'}'),
                            Text('Location: ${request['location'] ?? 'N/A'}'),
                          ],
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
                            status.toUpperCase(),
                            style: TextStyle(
                              color: statusColor,
                              fontWeight: FontWeight.bold,
                              fontSize: 12,
                            ),
                          ),
                        ),
                        onTap: () => _showFixDialog(context, request),
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

  Widget _buildPurchasesView() {
    final itemNameController = TextEditingController();
    final quantityController = TextEditingController(text: '1');
    final reasonController = TextEditingController();

    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Create Purchase Request',
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 24),
            Card(
              elevation: 2,
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    TextField(
                      controller: itemNameController,
                      decoration: const InputDecoration(
                        labelText: 'Item Name *',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.shopping_bag),
                      ),
                    ),
                    const SizedBox(height: 16),
                    TextField(
                      controller: quantityController,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: 'Quantity *',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.add_circle),
                      ),
                    ),
                    const SizedBox(height: 16),
                    TextField(
                      controller: reasonController,
                      maxLines: 3,
                      decoration: const InputDecoration(
                        labelText: 'Reason for Request',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.note),
                      ),
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () async {
                          if (itemNameController.text.isEmpty) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Please enter item name'),
                              ),
                            );
                            return;
                          }

                          final success = await _apiService
                              .createPurchaseRequest(
                                widget.userId,
                                itemNameController.text,
                                int.tryParse(quantityController.text) ?? 1,
                                reasonController.text,
                              );

                          if (mounted) {
                            if (success) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('Purchase request submitted!'),
                                  backgroundColor: Colors.green,
                                ),
                              );
                              // Clear form
                              itemNameController.clear();
                              quantityController.text = '1';
                              reasonController.clear();
                            } else {
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('Failed to submit request'),
                                  backgroundColor: Colors.red,
                                ),
                              );
                            }
                          }
                        },
                        icon: const Icon(Icons.send),
                        label: const Text('Submit Request'),
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.all(16),
                          backgroundColor: Colors.teal,
                          foregroundColor: Colors.white,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showFixDialog(BuildContext context, Map<String, dynamic> request) {
    final notesController = TextEditingController();

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Mark as Fixed'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text('Mark "${request['name']}" as fixed?'),
              const SizedBox(height: 16),
              TextField(
                controller: notesController,
                decoration: const InputDecoration(
                  labelText: 'Notes (optional)',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () async {
                final success = await _apiService.fixItem(
                  request['id'],
                  notesController.text,
                );

                if (mounted) {
                  Navigator.pop(context);
                  if (success) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Item marked as fixed')),
                    );
                    setState(() {
                      requestsFuture = _apiService.getMaintenanceRequests(
                        widget.userId,
                      );
                    });
                  } else {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Failed to update')),
                    );
                  }
                }
              },
              child: const Text('Mark Fixed'),
            ),
          ],
        );
      },
    );
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'fixed':
        return Colors.green;
      case 'pending':
        return Colors.orange;
      case 'in_progress':
        return Colors.blue;
      default:
        return Colors.grey;
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
