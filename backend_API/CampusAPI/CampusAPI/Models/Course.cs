public class Course
{
    public int CourseId { get; set; }
    public string CourseName { get; set; }

    // ADD THIS: To link the course to the teacher
    public int InstructorId { get; set; }

    // Optional: Navigation property to get teacher details
    public User Instructor { get; set; }
    public ICollection<Enrollment> Enrollments { get; set; }
}