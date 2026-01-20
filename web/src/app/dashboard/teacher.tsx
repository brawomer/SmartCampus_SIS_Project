import { useState, useEffect } from 'react';
import { BookOpen, Users, LogOut, LayoutDashboard, Star, AlertTriangle } from 'lucide-react';

// 1. Define Interfaces to fix "type never" errors
interface Course {
  courseId: number;
  courseName: string;
}

interface Student {
  enrollmentId: number;
  fullName: string;
  grade: number | null;
}

export default function TeacherDashboard() {
  // 2. Initialize state with Types
  const [courses, setCourses] = useState<Course[]>([]);
  const [students, setStudents] = useState<Student[]>([]);
  const [selectedCourse, setSelectedCourse] = useState<number | null>(null);
  
  // Get the teacher ID from localStorage (stored during login)
  const teacherId = localStorage.getItem('userId'); 

  // 3. Fetch Courses assigned to this Teacher
  useEffect(() => {
    fetch(`https://localhost:7229/api/Teacher/my-courses/${teacherId}`)
      .then(res => res.json())
      .then((data: Course[]) => setCourses(data))
      .catch(err => console.error("Error fetching courses:", err));
  }, []);

  // 4. Fetch Students and Grades for selected class
  const handleCourseClick = (courseId: number) => {
    fetch(`https://localhost:7229/api/Teacher/course-students/${courseId}`)
      .then(res => res.json())
      .then((data: Student[]) => {
        setStudents(data);
        setSelectedCourse(courseId);
      })
      .catch(err => console.error("Error fetching students:", err));
  };

  return (
    <div className="min-h-screen w-full bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-6 flex font-sans">
      {/* Sidebar Navigation */}
      <aside className="w-64 backdrop-blur-xl bg-white/10 rounded-3xl border border-white/20 p-6 flex flex-col space-y-8">
        <div className="flex items-center space-x-3 text-white">
          <div className="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-bold text-xl">T</div>
          <span className="font-bold text-lg">Teacher Portal</span>
        </div>
        
        <nav className="flex-1 space-y-4">
          <button className="flex items-center space-x-3 text-white w-full p-3 rounded-xl bg-white/20 shadow-lg">
            <LayoutDashboard className="w-5 h-5" /> <span>Dashboard</span>
          </button>
          <button className="flex items-center space-x-3 text-white/70 w-full p-3 rounded-xl hover:bg-white/10 transition-all">
            <BookOpen className="w-5 h-5" /> <span>My Courses</span>
          </button>
        </nav>

        <button onClick={() => window.location.href = '/'} className="flex items-center space-x-3 text-white/70 hover:text-white p-3 transition-colors">
          <LogOut className="w-5 h-5" /> <span>Logout</span>
        </button>
      </aside>

      {/* Main Content Area */}
      <main className="flex-1 ml-6 space-y-6">
        <header className="flex justify-between items-center text-white">
          <div>
            <h1 className="text-3xl font-bold">Classroom Management</h1>
            <p className="text-white/60 text-sm">Graduation Project - April 2026</p>
          </div>
          <button className="bg-red-500/20 hover:bg-red-500/40 text-white px-4 py-2 rounded-xl border border-red-500/30 flex items-center transition-all">
            <AlertTriangle className="w-4 h-4 mr-2" /> Report Broken Item
          </button>
        </header>

        {/* Course Selection Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {courses.map((course) => (
            <div 
              key={course.courseId}
              onClick={() => handleCourseClick(course.courseId)}
              className={`p-6 rounded-3xl border cursor-pointer transition-all transform hover:scale-[1.02] active:scale-95 ${
                selectedCourse === course.courseId 
                ? 'bg-white text-blue-600 shadow-2xl' 
                : 'backdrop-blur-xl bg-white/10 border-white/20 text-white hover:bg-white/20'
              }`}
            >
              <div className="bg-blue-400/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                <BookOpen className={selectedCourse === course.courseId ? 'text-blue-600' : 'text-white'} />
              </div>
              <h3 className="text-xl font-bold mb-1">{course.courseName}</h3>
              <p className="text-sm opacity-60">ID: {course.courseId}</p>
            </div>
          ))}
        </div>

        {/* Student Grading Table */}
        {selectedCourse && (
          <div className="backdrop-blur-xl bg-white/10 rounded-3xl border border-white/20 p-8 shadow-2xl animate-in fade-in slide-in-from-bottom-4">
            <div className="flex items-center justify-between mb-8">
              <div className="flex items-center space-x-3 text-white">
                <Users className="w-7 h-7" />
                <h2 className="text-2xl font-bold">Student Roster</h2>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-white text-left">
                <thead>
                  <tr className="border-b border-white/20">
                    <th className="pb-4 font-semibold text-sm uppercase tracking-wider">Student Name</th>
                    <th className="pb-4 font-semibold text-sm uppercase tracking-wider text-center">Grade</th>
                    <th className="pb-4 font-semibold text-sm uppercase tracking-wider text-right">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-white/5">
                  {students.map((student) => (
                    <tr key={student.enrollmentId} className="group hover:bg-white/5 transition-colors">
                      <td className="py-5">
                        <div className="font-medium text-lg">{student.fullName}</div>
                        <div className="text-xs text-white/40">ID: {student.enrollmentId}</div>
                      </td>
                      <td className="py-5 text-center">
                        <span className="px-4 py-1.5 bg-white/10 rounded-full text-yellow-300 font-mono font-bold border border-white/10">
                          {student.grade !== null ? `${student.grade}%` : '---'}
                        </span>
                      </td>
                      <td className="py-5 text-right">
                        <button className="inline-flex items-center space-x-2 px-4 py-2 bg-white text-blue-600 rounded-xl font-bold text-sm hover:bg-blue-50 transition-all shadow-lg active:transform active:scale-95">
                          <Star className="w-4 h-4" />
                          <span>Give Score</span>
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </main>
    </div>
  );
}