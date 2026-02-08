# SmartCampus Mobile API Setup Guide

## Overview
The Flutter mobile app is now connected to fetch real data from your Laravel backend. Follow this guide to set up the required API endpoints.

---

## API Endpoints Required

### 1. **Login Endpoint**
**URL:** `POST /api/login`

**Request Body:**
```json
{
  "email": "student@example.com",
  "password": "password123"
}
```

**Response (Success - 200):**
```json
{
  "token": "sanctum_token_here",
  "user": {
    "id": 1,
    "name": "Student Name",
    "email": "student@example.com",
    "role": "student"
  }
}
```

---

### 2. **Get Lectures Endpoint**
**URL:** `GET /api/lectures`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200):**
```json
[
  {
    "id": 1,
    "title": "Mathematics 101",
    "instructor": "Prof. Ahmed",
    "time": "10:00 AM",
    "room": "Room 101"
  },
  {
    "id": 2,
    "title": "Physics Basics",
    "instructor": "Prof. Sarah",
    "time": "01:00 PM",
    "room": "Lab 2"
  }
]
```

---

### 3. **Get Grades Endpoint**
**URL:** `GET /api/grades`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200):**
```json
[
  {
    "id": 1,
    "subject": "Mathematics",
    "grade": "A",
    "percentage": 92
  },
  {
    "id": 2,
    "subject": "Physics",
    "grade": "B+",
    "percentage": 88
  },
  {
    "id": 3,
    "subject": "Chemistry",
    "grade": "A-",
    "percentage": 90
  },
  {
    "id": 4,
    "subject": "English",
    "grade": "B",
    "percentage": 85
  }
]
```

---

### 4. **Get Attendance Endpoint**
**URL:** `GET /api/attendance`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200):**
```json
[
  {
    "id": 1,
    "date": "2026-01-30",
    "status": "Present"
  },
  {
    "id": 2,
    "date": "2026-01-29",
    "status": "Present"
  },
  {
    "id": 3,
    "date": "2026-01-28",
    "status": "Absent"
  },
  {
    "id": 4,
    "date": "2026-01-27",
    "status": "Present"
  },
  {
    "id": 5,
    "date": "2026-01-26",
    "status": "Late"
  }
]
```

---

## Laravel Backend Setup Example

### 1. Create API Routes (routes/api.php)
```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/lectures', [StudentController::class, 'getLectures']);
    Route::get('/grades', [StudentController::class, 'getGrades']);
    Route::get('/attendance', [StudentController::class, 'getAttendance']);
    Route::post('/reports/scan', [StudentController::class, 'reportBrokenItem']);
});
```

### 2. Auth Controller Example
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }
}
```

### 3. Student Controller Example
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getLectures(Request $request)
    {
        $student = $request->user();
        
        // Get lectures for the student
        $lectures = $student->lectures()->select('id', 'title', 'instructor', 'time', 'room')->get();
        
        return response()->json($lectures, 200);
    }

    public function getGrades(Request $request)
    {
        $student = $request->user();
        
        // Get grades for the student
        $grades = $student->grades()->select('id', 'subject', 'grade', 'percentage')->get();
        
        return response()->json($grades, 200);
    }

    public function getAttendance(Request $request)
    {
        $student = $request->user();
        
        // Get attendance records for the student
        $attendance = $student->attendance()->select('id', 'date', 'status')->orderBy('date', 'desc')->get();
        
        return response()->json($attendance, 200);
    }

    public function reportBrokenItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'description' => 'required|string',
        ]);

        // Create report
        $report = $request->user()->reports()->create($validated);

        return response()->json(['message' => 'Report created successfully'], 201);
    }
}
```

---

## Configuration

### Mobile App Settings (lib/api_service.dart)

**To switch from Test Mode to Real API:**

```dart
// Change this line from:
static const bool useTestMode = true;

// To:
static const bool useTestMode = false;
```

**Update the base URL if needed:**
```dart
final String baseUrl = "http://YOUR_SERVER_IP:8000/api";
```

---

## Testing

### Using Test Mode
Test mode is currently enabled. The app will:
- Use sample data
- Simulate network delays
- No backend required

### Testing with Real Backend
1. Ensure your Laravel server is running
2. Update the IP address in `api_service.dart`
3. Set `useTestMode = false`
4. Login with valid credentials
5. Check the console for any errors

---

## Troubleshooting

### "Failed to fetch" Error
- Check if your Laravel backend is running
- Verify the IP address is correct
- Ensure the endpoint exists
- Check CORS settings if accessing from different domain

### Token Not Saving
- Ensure the login response includes a `token` field
- Verify the user's email and password are correct
- Check if Laravel Sanctum is properly configured

### No Data Displaying
- Verify the API endpoints return the correct JSON structure
- Check if the authenticated user has associated data
- Ensure the Authorization header is being sent

---

## Next Steps

1. Create the database tables for lectures, grades, and attendance
2. Implement the controller methods as shown
3. Test each endpoint with Postman first
4. Integrate with your existing student management system
5. Update the app's base URL and set `useTestMode = false`

