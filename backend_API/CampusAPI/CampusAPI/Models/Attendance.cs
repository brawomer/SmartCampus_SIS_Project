using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace CampusAPI.Models
{
    public class Attendance
    {
        [Key]
        public int AttendanceId { get; set; }

        public int EnrollmentId { get; set; }

        [ForeignKey("EnrollmentId")]
        public Enrollment? Enrollment { get; set; } // This links it to the student

        public DateTime AttendanceDate { get; set; } = DateTime.Now;

        public string Status { get; set; } = "Present";

        public int MinutesLate { get; set; } = 0;
    }
}