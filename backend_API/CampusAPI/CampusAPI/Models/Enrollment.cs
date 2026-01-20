public class Enrollment
{
    public int EnrollmentId { get; set; }
    public int UserId { get; set; }
    public int CourseId { get; set; }

    // Add these fields to match your TeacherController
    public double LabTask1 { get; set; }
    public double LabTask2 { get; set; }
    public double GroupWork { get; set; }
    public double DailyActivity { get; set; }
    public double Report { get; set; }
    public double Seminar { get; set; }
    public double Midterm { get; set; }
    public double Final { get; set; }

    public User Student { get; set; }
    public Course Course { get; set; }
}