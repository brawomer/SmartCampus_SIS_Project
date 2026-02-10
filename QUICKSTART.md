# SmartCampus Enterprise - Quick Start Guide

## 🚀 Getting Started

### 1. Start the Server
```bash
cd ~/Documents/GitHub/SmartCampus_SIS_Project
php -S localhost:8000 -t webb
```

### 2. Initialize Sample Data
Visit: **http://localhost:8000/init-sample-data.php**

### 3. Login

| Role | Username | Password |
|------|----------|----------|
| **Student** | `student_alex` | `password123` |
| **Teacher** | `teacher_jane` | `password123` |
| **Admin** | `admin_user` | `password123` |

---

## 📚 Feature Overview

### Student Portal
- **Courses** (`/student/courses.php`) - View enrolled courses
- **Grades** (`/student/grades.php`) - View grades and GPA
- **Dashboard** (`/student/dashboard.php`) - Overview and quick stats

### Teacher Portal
- **Gradebook** (`/head/gradebook.php`) - Enter and manage grades
- **Dashboard** (`/head/dashboard.php`) - Teaching overview

### Admin Portal
- **User Management** (`/admin/users-manage.php`) - Create, view, delete users
- **Dashboard** (`/admin/dashboard.php`) - System overview

---

## 🛠️ Technical Stack

- **Backend**: PHP 7.4+ with file-based JSON storage
- **Frontend**: Tailwind CSS + FontAwesome
- **Security**: CSRF tokens, password hashing, RBAC
- **Architecture**: MVC-inspired with middleware

---

## 📁 Key Files

- `config.php` - Application configuration
- `db.php` - Enhanced database layer (11 collections)
- `includes/helpers.php` - 30+ utility functions
- `middleware/auth.php` - Authentication & authorization

---

## 🔐 Security Features

✅ Password hashing (bcrypt)  
✅ CSRF protection  
✅ XSS prevention  
✅ Session timeout  
✅ Role-based access control  

---

## 📊 Sample Data Included

- 2 Courses (CS101, CS201)
- 5 Users (admin, teacher, student, technician, parent)
- 2 Enrollments
- 5 Grade entries

---

## 🎯 Next Steps

1. Explore each role's dashboard
2. Test grade entry as teacher
3. View grades as student
4. Create new users as admin
5. Customize for your needs!

---

For full documentation, see [walkthrough.md](file:///home/molotov/.gemini/antigravity/brain/2667a491-6973-4b95-9135-62be069e5481/walkthrough.md)
