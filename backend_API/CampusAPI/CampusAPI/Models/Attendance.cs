public class Attendance
{
    public int AttendanceId { get; set; }

    // Ensure these names match EXACTLY what is in the controller
    public int UserId { get; set; }
    public int CourseId { get; set; }

    public string Status { get; set; } // "Present" or "Absent"
    public DateTime Date { get; set; }

    // Navigation properties
    public User Student { get; set; }
    public Course Course { get; set; }
}