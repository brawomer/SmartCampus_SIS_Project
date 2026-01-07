using System;
using System.ComponentModel.DataAnnotations;

namespace CampusAPI.Models
{
    public class Attendance
    {
        [Key]
        public int AttendanceId { get; set; }
        public int EnrollmentId { get; set; }
        public DateTime AttendanceDate { get; set; }
        public string Status { get; set; } // "Present", "Late", "Absent"
        public int MinutesLate { get; set; }
    }
}