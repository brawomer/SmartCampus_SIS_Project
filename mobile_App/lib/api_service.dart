import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  final String baseUrl = "http://10.53.206.1:8000/api"; // Your Computer's IP
  String? _token; // This stores your Sanctum Token

  // 1. Mobile Login Function
  Future<bool> login(String email, String password) async {
    final response = await http.post(
      Uri.parse("$baseUrl/login"),
      body: {'email': email, 'password': password},
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      _token = data['token']; // Save the token for future scans
      return true;
    }
    return false;
  }

  // 2. Scan & Report Broken Item
  Future<void> reportBrokenItem(String itemId, String description) async {
    if (_token == null) return;

    final response = await http.post(
      Uri.parse("$baseUrl/reports/scan"),
      headers: {
        'Authorization': 'Bearer $_token', // Send the Sanctum Token
        'Accept': 'application/json',
      },
      body: {
        'item_id': itemId,
        'description': description,
      },
    );

    if (response.statusCode == 201) {
      print("Report successful!");
    }
  }
}