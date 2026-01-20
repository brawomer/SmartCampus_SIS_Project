using System;
using System.ComponentModel.DataAnnotations;

namespace CampusAPI.Models
{
    public class Attendance
    {
        [Key]
        public int AttendanceId { get; set; }

        // Link to the student and course
        public int UserId { get; set; }
        public int CourseId { get; set; }

        // Link to the enrollment
        public int EnrollmentId { get; set; }

        // Attendance details
        public DateTime AttendanceDate { get; set; } = DateTime.Now;
        public string Status { get; set; } = "Present"; // Present, Absent, Late
        public int MinutesLate { get; set; } = 0;

        // Navigation Properties
        public User Student { get; set; }
        public Course Course { get; set; }
    }
}