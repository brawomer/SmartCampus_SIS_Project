import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { LayoutDashboard, BookOpen, GraduationCap, LogOut, AlertCircle, Save } from 'lucide-react';

interface Course {
  courseId: number;
  courseName: string;
}

interface Student {
  enrollmentId: number;
  fullName: string;
  labTask1: number;
  labTask2: number;
  groupWork: number;
  dailyActivity: number;
  report: number;
  seminar: number;
  midterm: number;
  final: number;
  attendanceCount: number; // For your attendance requirements
}

export default function TeacherDashboard() {
  const navigate = useNavigate();
  const [courses, setCourses] = useState<Course[]>([]);
  const [students, setStudents] = useState<Student[]>([]);
  const [selectedCourse, setSelectedCourse] = useState<number | null>(null);
  const [activeTab, setActiveTab] = useState('dashboard');
  
  const teacherId = localStorage.getItem('userId');

  useEffect(() => {
    if (teacherId) {
      // Debug: Check your console to see if this is '4'
      console.log("Current Teacher ID:", teacherId); 
      fetch(`https://localhost:7229/api/Teacher/my-courses/${teacherId}`)
        .then(res => res.json())
        .then(data => setCourses(data))
        .catch(err => console.error("API Error:", err));
    }
  }, [teacherId]);

  const handleCourseClick = (courseId: number) => {
    fetch(`https://localhost:7229/api/Teacher/course-students/${courseId}`)
      .then(res => res.json())
      .then(data => {
        setStudents(data);
        setSelectedCourse(courseId);
        setActiveTab('grades');
      });
  };

  const calculateTotal = (s: Student) => {
    return (s.labTask1 + s.labTask2 + s.groupWork + s.dailyActivity + s.report + s.seminar + s.midterm + s.final).toFixed(1);
  };

  const handleSave = async (student: Student) => {
    await fetch('https://localhost:7229/api/Teacher/update-grade', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(student)
    });
    alert(`Data for ${student.fullName} saved!`);
  };

  return (
    <div className="flex min-h-screen w-full bg-gradient-to-br from-[#1e3a8a] via-[#0891b2] to-[#06b6d4]">
      {/* Sidebar */}
      <aside className="w-64 backdrop-blur-xl bg-white/10 border-r border-white/20 p-6">
        <h2 className="text-white text-xl font-bold mb-10">Smart Campus</h2>
        <nav className="space-y-4">
          <button onClick={() => setActiveTab('dashboard')} className={`w-full flex items-center gap-3 p-3 rounded-2xl text-white ${activeTab === 'dashboard' ? 'bg-white/20 border border-white/30' : ''}`}>
            <LayoutDashboard size={20} /> Dashboard
          </button>
          <button onClick={() => setActiveTab('grades')} className={`w-full flex items-center gap-3 p-3 rounded-2xl text-white ${activeTab === 'grades' ? 'bg-white/20 border border-white/30' : ''}`}>
            <GraduationCap size={20} /> Student Grades
          </button>
          <button onClick={() => { localStorage.clear(); navigate('/'); }} className="w-full flex items-center gap-3 p-3 text-white/60 hover:text-red-400 mt-10">
            <LogOut size={20} /> Logout
          </button>
        </nav>
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-8 text-white overflow-y-auto">
        <header className="flex justify-between items-center mb-10">
          <h1 className="text-3xl font-bold">Instructor Portal</h1>
          <button className="bg-red-500/20 px-4 py-2 rounded-xl border border-red-500/30 flex items-center gap-2">
            <AlertCircle size={18} /> Report Issue
          </button>
        </header>

        {activeTab === 'dashboard' && (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {courses.length > 0 ? courses.map(course => (
              <div key={course.courseId} onClick={() => handleCourseClick(course.courseId)} className="p-6 backdrop-blur-md bg-white/10 border border-white/20 rounded-3xl cursor-pointer hover:bg-white/20 transition-all shadow-xl">
                <BookOpen className="text-cyan-300 mb-4" />
                <h3 className="text-xl font-bold">{course.courseName}</h3>
                <p className="text-sm text-white/40 font-mono">ID: {course.courseId}</p>
              </div>
            )) : (
              <div className="col-span-full p-10 bg-white/5 border border-white/10 rounded-3xl text-center">
                <p className="text-white/60">No courses assigned to User ID: {teacherId}</p>
              </div>
            )}
          </div>
        )}

        {activeTab === 'grades' && selectedCourse && (
          <div className="backdrop-blur-xl bg-white/5 rounded-3xl border border-white/10 p-4 overflow-x-auto shadow-2xl">
            <table className="w-full text-left text-[11px] min-w-[1000px]">
              <thead className="border-b border-white/20 text-white/50 uppercase">
                <tr>
                  <th className="p-2">Name</th>
                  <th className="p-1 text-center text-cyan-400">Attendance</th>
                  <th className="p-1 text-center">Lab1</th><th className="p-1 text-center">Lab2</th>
                  <th className="p-1 text-center">Group</th><th className="p-1 text-center">Activity</th>
                  <th className="p-1 text-center">Report</th><th className="p-1 text-center">Seminar</th>
                  <th className="p-1 text-center">Midterm</th><th className="p-1 text-center">Final</th>
                  <th className="p-2 text-yellow-300 font-bold">Total</th>
                  <th className="p-2 text-right">Action</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {students.map(student => (
                  <tr key={student.enrollmentId} className="hover:bg-white/5 transition-colors">
                    <td className="p-2 font-medium">{student.fullName}</td>
                    <td className="p-1 text-center text-cyan-400 font-bold">{student.attendanceCount || 0}</td>
                    {['labTask1', 'labTask2', 'groupWork', 'dailyActivity', 'report', 'seminar', 'midterm', 'final'].map(field => (
                      <td key={field} className="p-1 text-center">
                        <input 
                          type="number" 
                          value={student[field as keyof Student] || 0} 
                          onChange={(e) => {
                            const val = parseFloat(e.target.value) || 0;
                            setStudents(prev => prev.map(s => s.enrollmentId === student.enrollmentId ? {...s, [field]: val} : s));
                          }}
                          className="w-10 bg-white/10 border border-white/20 rounded text-center py-1 outline-none"
                        />
                      </td>
                    ))}
                    <td className="p-2 text-yellow-300 font-black text-sm text-center">{calculateTotal(student)}</td>
                    <td className="p-2 text-right">
                      <button onClick={() => handleSave(student)} className="bg-white text-blue-800 p-1.5 rounded-lg font-bold shadow-lg hover:bg-cyan-50 transition-all"><Save size={16} /></button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </main>
    </div>
  );
}