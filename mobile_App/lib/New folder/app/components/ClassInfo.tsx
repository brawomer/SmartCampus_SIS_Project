import { Users, Calendar, Clock } from 'lucide-react';

interface ClassInfoProps {
  className: string;
  classTeacher: string;
  totalStudents: number;
  semester: string;
}

export function ClassInfo({ className, classTeacher, totalStudents, semester }: ClassInfoProps) {
  return (
    <div className="bg-white rounded-xl shadow-lg p-6">
      <h3 className="text-xl mb-4 text-gray-800">Class Information</h3>
      
      <div className="space-y-4">
        <div className="flex items-center gap-3">
          <div className="bg-indigo-100 p-3 rounded-lg">
            <Users className="w-5 h-5 text-indigo-600" />
          </div>
          <div>
            <p className="text-sm text-gray-600">Class</p>
            <p className="text-gray-900">{className}</p>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <div className="bg-purple-100 p-3 rounded-lg">
            <Users className="w-5 h-5 text-purple-600" />
          </div>
          <div>
            <p className="text-sm text-gray-600">Class Teacher</p>
            <p className="text-gray-900">{classTeacher}</p>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <div className="bg-blue-100 p-3 rounded-lg">
            <Users className="w-5 h-5 text-blue-600" />
          </div>
          <div>
            <p className="text-sm text-gray-600">Total Students</p>
            <p className="text-gray-900">{totalStudents}</p>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <div className="bg-green-100 p-3 rounded-lg">
            <Calendar className="w-5 h-5 text-green-600" />
          </div>
          <div>
            <p className="text-sm text-gray-600">Semester</p>
            <p className="text-gray-900">{semester}</p>
          </div>
        </div>
      </div>
    </div>
  );
}
