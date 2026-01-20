using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusAPI.Data;
using CampusAPI.Models;

namespace CampusAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class TeacherController : ControllerBase
    {
        private readonly AppDbContext _context;

        public TeacherController(AppDbContext context)
        {
            _context = context;
        }

        [HttpGet("course-students/{courseId}")]
        public async Task<IActionResult> GetStudentsInCourse(int courseId)
        {
            var enrollmentData = await _context.Enrollments
                .Where(e => e.CourseId == courseId)
                .Include(e => e.Student)
                .ToListAsync();

            var students = enrollmentData.Select(e => new {
                enrollmentId = e.EnrollmentId,
                fullName = e.Student?.FullName ?? "Unknown",
                labTask1 = e.LabTask1,
                labTask2 = e.LabTask2,
                groupWork = e.GroupWork,
                dailyActivity = e.DailyActivity,
                report = e.Report,
                seminar = e.Seminar,
                midterm = e.Midterm,
                final = e.Final,
                // Fixed attendance Count
                attendanceCount = _context.Attendances
                    .Count(a => a.UserId == e.UserId && a.CourseId == courseId && a.Status == "Present")
            }).ToList();

            return Ok(students);
        }

        [HttpGet("my-courses/{teacherId}")]
        public async Task<IActionResult> GetMyCourses(int teacherId)
        {
            var courses = await _context.Courses
                .Where(c => c.InstructorId == teacherId)
                .ToListAsync();
            return Ok(courses);
        }
    }
}