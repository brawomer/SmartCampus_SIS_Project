import { Clock, BookOpen } from 'lucide-react';

interface ClassSchedule {
  id: string;
  subject: string;
  teacher: string;
  time: string;
  room: string;
}

interface UpcomingClassesProps {
  schedule: ClassSchedule[];
}

export function UpcomingClasses({ schedule }: UpcomingClassesProps) {
  return (
    <div className="bg-white rounded-xl shadow-lg p-6">
      <h3 className="text-xl mb-4 text-gray-800">Today's Schedule</h3>
      
      <div className="space-y-3">
        {schedule.map((classItem) => (
          <div
            key={classItem.id}
            className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <div className="bg-indigo-600 p-3 rounded-lg">
              <BookOpen className="w-5 h-5 text-white" />
            </div>
            <div className="flex-1">
              <p className="text-gray-900">{classItem.subject}</p>
              <p className="text-sm text-gray-600">{classItem.teacher}</p>
            </div>
            <div className="text-right">
              <div className="flex items-center gap-1 text-sm text-gray-600 mb-1">
                <Clock className="w-4 h-4" />
                <span>{classItem.time}</span>
              </div>
              <p className="text-xs text-gray-500">Room {classItem.room}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
