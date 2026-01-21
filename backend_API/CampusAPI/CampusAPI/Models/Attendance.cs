using System;
using System.ComponentModel.DataAnnotations.Schema;

public class Attendance
{
    public int AttendanceId { get; set; }

    // Match the DB: Attendance references an Enrollment
    public int EnrollmentId { get; set; }

    public DateTime AttendanceDate { get; set; }
    public string Status { get; set; } // "Present" or "Absent"
    public int MinutesLate { get; set; }

    // Navigation
    [ForeignKey("EnrollmentId")]
    public Enrollment Enrollment { get; set; }
}