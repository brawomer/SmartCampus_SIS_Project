using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace CampusAPI.Models
{
    public class Enrollment
    {
        [Key]
        public int EnrollmentId { get; set; }
        public int UserId { get; set; } // The Student
        public int CourseId { get; set; } // The Subject

        [ForeignKey("UserId")]
        public User? Student { get; set; }

        [ForeignKey("CourseId")]
        public Course? Course { get; set; }
    }
}