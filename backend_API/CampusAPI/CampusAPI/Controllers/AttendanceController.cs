using CampusAPI.Data;
using CampusAPI.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace CampusAPI.Controllers
{

    [Route("api/[controller]")]
    [ApiController]
    public class AttendanceController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AttendanceController(AppDbContext context)
        {
            _context = context;
        }

        [HttpPost("scan")]
        public async Task<IActionResult> MarkAttendance(int enrollmentId)
        {
            // 1. Set the class start time (for testing, let's say 8:00 AM today)
            var classStartTime = DateTime.Today.AddHours(8);
            var scanTime = DateTime.Now;

            // 2. Calculate the difference in minutes
            var timeDifference = (scanTime - classStartTime).TotalMinutes;

            string finalStatus;
            int lateMins = 0;

            if (timeDifference <= 10)
            {
                finalStatus = "Present";
            }
            else
            {
                finalStatus = "Late";
                lateMins = (int)timeDifference;
            }

            // 3. Save the record to the database
            var newRecord = new Attendance
            {
                EnrollmentId = enrollmentId,
                AttendanceDate = scanTime,
                Status = finalStatus,
                MinutesLate = lateMins
            };

            _context.Attendances.Add(newRecord); // Use the 's' we fixed earlier!
            await _context.SaveChangesAsync();

            return Ok(new { Message = $"Success! You are marked as {finalStatus}.", Delay = lateMins });
        }

        // GET: api/Attendance/stats/{enrollmentId}
        [HttpGet("stats/{enrollmentId}")]
        public async Task<IActionResult> GetStudentStats(int enrollmentId)
        {
            // 1. Get all attendance records for this student
            var records = await _context.Attendances
                .Where(a => a.EnrollmentId == enrollmentId)
                .ToListAsync();

            if (records.Count == 0) return Ok(new { Message = "No records found for this student." });

            // 2. Calculate the numbers
            int totalClasses = records.Count;
            int presentCount = records.Count(a => a.Status == "Present");
            int lateCount = records.Count(a => a.Status == "Late");
            int absentCount = records.Count(a => a.Status == "Absent");

            // 3. Calculate Percentage
            double attendanceRate = ((double)(presentCount + lateCount) / totalClasses) * 100;

            return Ok(new
            {
                TotalClasses = totalClasses,
                Present = presentCount,
                Late = lateCount,
                Absent = absentCount,
                AttendanceRate = Math.Round(attendanceRate, 2) + "%",
                Status = attendanceRate < 75 ? "Warning: Low Attendance" : "Good Standing"
            });
        }
    }

    public class AttendanceRequest
    {
        public int EnrollmentId { get; set; }
    }

}
