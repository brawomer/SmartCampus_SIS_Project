using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace CampusAPI.Models
{
    public class Attendance
    {
        [Key]
        public int AttendanceId { get; set; }
        public int EnrollmentId { get; set; } // Links student to a course
        public DateTime AttendanceDate { get; set; } = DateTime.Now;
        public string Status { get; set; } = "Absent"; // Present, Late, or Absent
        public int MinutesLate { get; set; } = 0;

        [ForeignKey("EnrollmentId")]
        public Enrollment? Enrollment { get; set; }
    }
}