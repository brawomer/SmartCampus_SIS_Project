
  import { createRoot } from "react-dom/client";
  import { BrowserRouter, Routes, Route } from "react-router-dom";
  import App from "./app/App.tsx";
  import TeacherDashboard from "./app/dashboard/teacher/dashboard.tsx";
  import "./styles/index.css";

  createRoot(document.getElementById("root")!).render(
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/dashboard/teacher" element={<TeacherDashboard />} />
      </Routes>
    </BrowserRouter>
  );
  