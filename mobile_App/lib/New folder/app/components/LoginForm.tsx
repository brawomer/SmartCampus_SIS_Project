import { useState } from 'react';
import { GraduationCap } from 'lucide-react';

interface LoginFormProps {
  onLogin: (studentId: string) => void;
}

export function LoginForm({ onLogin }: LoginFormProps) {
  const [studentId, setStudentId] = useState('');
  const [password, setPassword] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (studentId && password) {
      onLogin(studentId);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
      <div className="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <div className="flex flex-col items-center mb-8">
          <div className="bg-indigo-600 p-4 rounded-full mb-4">
            <GraduationCap className="w-12 h-12 text-white" />
          </div>
          <h1 className="text-3xl mb-2">Student Portal</h1>
          <p className="text-gray-600">Sign in to view your grades</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="studentId" className="block text-sm mb-2 text-gray-700">
              Student ID
            </label>
            <input
              id="studentId"
              type="text"
              value={studentId}
              onChange={(e) => setStudentId(e.target.value)}
              placeholder="Enter your student ID"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              required
            />
          </div>

          <div>
            <label htmlFor="password" className="block text-sm mb-2 text-gray-700">
              Password
            </label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Enter your password"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              required
            />
          </div>

          <button
            type="submit"
            className="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-colors"
          >
            Sign In
          </button>
        </form>

        <div className="mt-6 p-4 bg-blue-50 rounded-lg">
          <p className="text-sm text-gray-700 mb-2">Demo Credentials:</p>
          <p className="text-xs text-gray-600">Student ID: STU001, STU002, STU003</p>
          <p className="text-xs text-gray-600">Password: Any password</p>
        </div>
      </div>
    </div>
  );
}
