using Microsoft.AspNetCore.Mvc;
using CampusAPI.Data;
using CampusAPI.Models;

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
        public async Task<IActionResult> MarkAttendance([FromBody] AttendanceRequest request)
        {
            // For testing, let's say the class starts at 8:30 AM today
            var classStartTime = DateTime.Today.AddHours(8).AddMinutes(30);
            var scanTime = DateTime.Now;

            var diff = (scanTime - classStartTime).TotalMinutes;
            string finalStatus = "Present";
            int lateMins = 0;

            if (diff > 10)
            {
                finalStatus = "Late";
                lateMins = (int)diff;
            }

            var newRecord = new Attendance
            {
                EnrollmentId = request.EnrollmentId,
                AttendanceDate = scanTime,
                Status = finalStatus,
                MinutesLate = lateMins
            };

            _context.Attendances.Add(newRecord);
            await _context.SaveChangesAsync();

            return Ok(new { Message = $"Student marked as {finalStatus}", Lateness = lateMins });
        }
    }

    public class AttendanceRequest
    {
        public int EnrollmentId { get; set; }
    }
}