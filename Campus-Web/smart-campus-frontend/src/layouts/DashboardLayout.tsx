import { type ReactNode } from "react";

interface Props {
  children: ReactNode;
}

const DashboardLayout = ({ children }: Props) => {
  return (
    <div className="flex min-h-screen bg-gray-100">
      {/* Sidebar */}
      <aside className="w-64 bg-white shadow-md">
        <div className="p-6 font-bold text-xl text-blue-600">
          Smart Campus
        </div>

        <nav className="px-4 space-y-2">
          <a className="block px-4 py-2 rounded-lg bg-blue-100 text-blue-700">
            Dashboard
          </a>
          <a className="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Courses
          </a>
          <a className="block px-4 py-2 rounded-lg hover:bg-gray-100">
            Attendance
          </a>
        </nav>
      </aside>

      {/* Main Content */}
      <div className="flex-1">
        {/* Topbar */}
        <header className="bg-white shadow-sm p-4 flex justify-between">
          <span className="font-medium">Welcome</span>
          <button className="text-red-500">Logout</button>
        </header>

        {/* Page Content */}
        <main className="p-6">{children}</main>
      </div>
    </div>
  );
};

export default DashboardLayout;
