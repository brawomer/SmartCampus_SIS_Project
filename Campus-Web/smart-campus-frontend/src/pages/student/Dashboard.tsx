import DashboardLayout from "../../layouts/DashboardLayout";

const StudentDashboard = () => {
  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-4">
        Student Dashboard
      </h1>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-white p-6 rounded-xl shadow">
          <p className="text-gray-500">Courses</p>
          <h2 className="text-3xl font-bold">5</h2>
        </div>

        <div className="bg-white p-6 rounded-xl shadow">
          <p className="text-gray-500">Attendance</p>
          <h2 className="text-3xl font-bold">92%</h2>
        </div>

        <div className="bg-white p-6 rounded-xl shadow">
          <p className="text-gray-500">GPA</p>
          <h2 className="text-3xl font-bold">3.6</h2>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default StudentDashboard;
