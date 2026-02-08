# SmartCampus Mobile App - Login Credentials

## 📚 Student Login

**Email:** `student@example.com` (or any email without "teacher" in it)
**Password:** `123456`

**Dashboard Features:**
- View upcoming lectures
- Check grades and scores
- View attendance records
- Bottom navigation with 3 tabs

---

## 👨‍🏫 Teacher Login

**Email:** `teacher@example.com`
**Password:** `teacher123`

**Dashboard Features:**
- **Grades Tab** - View and manage student grades
  - See list of all students
  - Click on student to view/add grades
  - Add new grades with subject, letter grade, and percentage
  
- **Attendance Tab** - Mark student attendance
  - Mark attendance as Present/Absent/Late
  - View attendance history
  - Quick marking system

- **Lectures Tab** - View your assigned lectures/classes
  - View class name and room
  - See lecture times
  - View number of students in each class

---

## 🔧 How Authentication Works

### Current (Test Mode)
- Uses predefined credentials
- No backend required
- Instant login with sample data

### When Backend is Ready
1. Change `useTestMode = false` in `api_service.dart`
2. Update the IP address: `final String baseUrl = "http://YOUR_IP:8000/api";`
3. Teacher credentials will be validated against your Laravel backend

---

## 📝 Predefined Teacher Credentials (in code)

Location: `lib/screens.dart`

```dart
static const String teacherEmail = 'teacher@example.com';
static const String teacherPassword = 'teacher123';
```

---

## 🚀 Quick Test Flow

### As a Student:
1. Launch app
2. Email: `student@example.com`
3. Password: `123456`
4. See student dashboard with lectures, grades, attendance

### As a Teacher:
1. Launch app
2. Email: `teacher@example.com`
3. Password: `teacher123`
4. See teacher dashboard with 3 tabs
5. Click on any student to add grades or mark attendance

---

## 🔐 Security Notes

- Teacher email is hardcoded for test mode
- When connected to backend, implement proper authentication
- Consider using Laravel Sanctum for token-based auth
- Store sensitive credentials securely
- Never commit real passwords to version control

