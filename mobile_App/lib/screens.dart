import 'package:flutter/material.dart';
import 'api_service.dart'; // To call your Laravel API
import 'dashboard.dart'; // To move to the dashboard after login

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final ApiService _apiService = ApiService(); // Connect to your API logic

  // THIS IS THE MISSING FUNCTION!
  void _handleLogin() async {
    // 1. Call your real Laravel Backend
    bool success = await _apiService.login(
      emailController.text,
      passwordController.text,
    );

    if (success) {
      // 2. Decide the role (you can improve this later with a real API response)
      String role = emailController.text.contains('teacher')
          ? 'Teacher'
          : 'Student';

      // 3. Move to the dashboard you created
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => DashboardScreen(role: role)),
      );
    } else {
      // Show error if login fails
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Invalid Email or Password')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.school, size: 80, color: Colors.blue),
            const SizedBox(height: 20),
            TextField(
              controller: emailController,
              decoration: const InputDecoration(
                labelText: 'Email',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 15),
            TextField(
              controller: passwordController,
              obscureText: true,
              decoration: const InputDecoration(
                labelText: 'Password',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _handleLogin, // This MUST point to your function
                child: const Text('Login'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
