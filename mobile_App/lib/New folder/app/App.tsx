import { useState } from 'react';
import { LoginForm } from './components/LoginForm';
import { GradesTable } from './components/GradesTable';
import { ClassInfo } from './components/ClassInfo';
import { UpcomingClasses } from './components/UpcomingClasses';
import { LogOut, User } from 'lucide-react';

// Mock data for different students
const studentData = {
  STU001: {
    name: 'Emma Johnson',
    className: 'Grade 10-A',
    classTeacher: 'Ms. Sarah Williams',
    totalStudents: 32,
    semester: 'Spring 2026',
    grades: [
      { id: '1', subject: 'Mathematics', teacher: 'Mr. Robert Chen', grade: 'A', percentage: 92, credits: 4 },
      { id: '2', subject: 'English Literature', teacher: 'Ms. Sarah Williams', grade: 'A-', percentage: 88, credits: 3 },
      { id: '3', subject: 'Physics', teacher: 'Dr. Michael Brown', grade: 'B+', percentage: 85, credits: 4 },
      { id: '4', subject: 'Chemistry', teacher: 'Dr. Lisa Anderson', grade: 'A', percentage: 91, credits: 4 },
      { id: '5', subject: 'History', teacher: 'Mr. James Taylor', grade: 'B', percentage: 82, credits: 3 },
      { id: '6', subject: 'Physical Education', teacher: 'Coach Martinez', grade: 'A', percentage: 95, credits: 2 },
    ],
    schedule: [
      { id: '1', subject: 'Mathematics', teacher: 'Mr. Robert Chen', time: '8:00 AM', room: '101' },
      { id: '2', subject: 'Physics', teacher: 'Dr. Michael Brown', time: '9:30 AM', room: '205' },
      { id: '3', subject: 'English Literature', teacher: 'Ms. Sarah Williams', time: '11:00 AM', room: '103' },
      { id: '4', subject: 'Chemistry', teacher: 'Dr. Lisa Anderson', time: '1:00 PM', room: '207' },
    ],
  },
  STU002: {
    name: 'Alex Martinez',
    className: 'Grade 10-B',
    classTeacher: 'Mr. David Thompson',
    totalStudents: 30,
    semester: 'Spring 2026',
    grades: [
      { id: '1', subject: 'Mathematics', teacher: 'Mr. Robert Chen', grade: 'B+', percentage: 87, credits: 4 },
      { id: '2', subject: 'English Literature', teacher: 'Ms. Sarah Williams', grade: 'A', percentage: 90, credits: 3 },
      { id: '3', subject: 'Physics', teacher: 'Dr. Michael Brown', grade: 'B', percentage: 81, credits: 4 },
      { id: '4', subject: 'Chemistry', teacher: 'Dr. Lisa Anderson', grade: 'B+', percentage: 86, credits: 4 },
      { id: '5', subject: 'History', teacher: 'Mr. James Taylor', grade: 'A-', percentage: 89, credits: 3 },
      { id: '6', subject: 'Physical Education', teacher: 'Coach Martinez', grade: 'A', percentage: 93, credits: 2 },
    ],
    schedule: [
      { id: '1', subject: 'English Literature', teacher: 'Ms. Sarah Williams', time: '8:00 AM', room: '103' },
      { id: '2', subject: 'Mathematics', teacher: 'Mr. Robert Chen', time: '9:30 AM', room: '101' },
      { id: '3', subject: 'History', teacher: 'Mr. James Taylor', time: '11:00 AM', room: '104' },
      { id: '4', subject: 'Physics', teacher: 'Dr. Michael Brown', time: '1:00 PM', room: '205' },
    ],
  },
  STU003: {
    name: 'Sophia Lee',
    className: 'Grade 10-A',
    classTeacher: 'Ms. Sarah Williams',
    totalStudents: 32,
    semester: 'Spring 2026',
    grades: [
      { id: '1', subject: 'Mathematics', teacher: 'Mr. Robert Chen', grade: 'A+', percentage: 98, credits: 4 },
      { id: '2', subject: 'English Literature', teacher: 'Ms. Sarah Williams', grade: 'A', percentage: 94, credits: 3 },
      { id: '3', subject: 'Physics', teacher: 'Dr. Michael Brown', grade: 'A', percentage: 93, credits: 4 },
      { id: '4', subject: 'Chemistry', teacher: 'Dr. Lisa Anderson', grade: 'A+', percentage: 97, credits: 4 },
      { id: '5', subject: 'History', teacher: 'Mr. James Taylor', grade: 'A', percentage: 91, credits: 3 },
      { id: '6', subject: 'Physical Education', teacher: 'Coach Martinez', grade: 'A', percentage: 96, credits: 2 },
    ],
    schedule: [
      { id: '1', subject: 'Chemistry', teacher: 'Dr. Lisa Anderson', time: '8:00 AM', room: '207' },
      { id: '2', subject: 'Mathematics', teacher: 'Mr. Robert Chen', time: '9:30 AM', room: '101' },
      { id: '3', subject: 'English Literature', teacher: 'Ms. Sarah Williams', time: '11:00 AM', room: '103' },
      { id: '4', subject: 'History', teacher: 'Mr. James Taylor', time: '1:00 PM', room: '104' },
    ],
  },
};

export default function App() {
  const [currentStudent, setCurrentStudent] = useState<string | null>(null);

  const handleLogin = (studentId: string) => {
    if (studentData[studentId as keyof typeof studentData]) {
      setCurrentStudent(studentId);
    }
  };

  const handleLogout = () => {
    setCurrentStudent(null);
  };

  if (!currentStudent) {
    return <LoginForm onLogin={handleLogin} />;
  }

  const student = studentData[currentStudent as keyof typeof studentData];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white shadow-sm border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
          <div className="flex items-center gap-3">
            <div className="bg-indigo-600 p-2 rounded-lg">
              <User className="w-6 h-6 text-white" />
            </div>
            <div>
              <h2 className="text-lg text-gray-900">{student.name}</h2>
              <p className="text-sm text-gray-600">{student.className}</p>
            </div>
          </div>
          <button
            onClick={handleLogout}
            className="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <LogOut className="w-5 h-5" />
            <span>Logout</span>
          </button>
        </div>
      </header>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
          <div className="lg:col-span-1">
            <ClassInfo
              className={student.className}
              classTeacher={student.classTeacher}
              totalStudents={student.totalStudents}
              semester={student.semester}
            />
          </div>
          <div className="lg:col-span-2">
            <UpcomingClasses schedule={student.schedule} />
          </div>
        </div>

        <GradesTable grades={student.grades} />
      </main>
    </div>
  );
}
