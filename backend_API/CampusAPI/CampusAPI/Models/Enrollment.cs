using System.ComponentModel.DataAnnotations.Schema;

public class Enrollment
{
    public int EnrollmentId { get; set; }

    // Match the DB column name
    public int StudentId { get; set; }

    public int CourseId { get; set; }

    public double LabTask1 { get; set; }
    public double LabTask2 { get; set; }
    public double GroupWork { get; set; }
    public double DailyActivity { get; set; }
    public double Report { get; set; }
    public double Seminar { get; set; }
    public double Midterm { get; set; }
    public double Final { get; set; }

    // Navigation properties
    [ForeignKey("StudentId")]
    public User Student { get; set; }
    public Course Course { get; set; }
}