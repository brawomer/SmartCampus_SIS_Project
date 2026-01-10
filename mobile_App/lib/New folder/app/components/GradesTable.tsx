interface Grade {
  id: string;
  subject: string;
  teacher: string;
  grade: string;
  percentage: number;
  credits: number;
}

interface GradesTableProps {
  grades: Grade[];
}

export function GradesTable({ grades }: GradesTableProps) {
  const getGradeColor = (percentage: number) => {
    if (percentage >= 90) return 'text-green-600 bg-green-50';
    if (percentage >= 80) return 'text-blue-600 bg-blue-50';
    if (percentage >= 70) return 'text-yellow-600 bg-yellow-50';
    return 'text-red-600 bg-red-50';
  };

  const totalCredits = grades.reduce((sum, grade) => sum + grade.credits, 0);
  const weightedSum = grades.reduce((sum, grade) => sum + (grade.percentage * grade.credits), 0);
  const gpa = (weightedSum / (totalCredits * 25)).toFixed(2);

  return (
    <div className="bg-white rounded-xl shadow-lg overflow-hidden">
      <div className="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
        <h2 className="text-2xl text-white mb-2">Grade Report</h2>
        <p className="text-indigo-100">Current Semester</p>
      </div>

      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-4 text-left text-sm text-gray-700">Subject</th>
              <th className="px-6 py-4 text-left text-sm text-gray-700">Teacher</th>
              <th className="px-6 py-4 text-center text-sm text-gray-700">Credits</th>
              <th className="px-6 py-4 text-center text-sm text-gray-700">Percentage</th>
              <th className="px-6 py-4 text-center text-sm text-gray-700">Grade</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {grades.map((grade) => (
              <tr key={grade.id} className="hover:bg-gray-50 transition-colors">
                <td className="px-6 py-4 text-gray-900">{grade.subject}</td>
                <td className="px-6 py-4 text-gray-600">{grade.teacher}</td>
                <td className="px-6 py-4 text-center text-gray-900">{grade.credits}</td>
                <td className="px-6 py-4 text-center text-gray-900">{grade.percentage}%</td>
                <td className="px-6 py-4 text-center">
                  <span className={`px-3 py-1 rounded-full text-sm ${getGradeColor(grade.percentage)}`}>
                    {grade.grade}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div className="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
        <div>
          <p className="text-sm text-gray-600">Total Credits</p>
          <p className="text-xl text-gray-900">{totalCredits}</p>
        </div>
        <div>
          <p className="text-sm text-gray-600">Current GPA</p>
          <p className="text-xl text-indigo-600">{gpa}</p>
        </div>
      </div>
    </div>
  );
}
