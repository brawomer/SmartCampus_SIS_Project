using System.ComponentModel.DataAnnotations;

namespace CampusAPI.Models
{
    public class Course
    {
        [Key]
        public int CourseId { get; set; }

        [Required]
        public string CourseName { get; set; } = string.Empty;

        public int DeptId { get; set; }
    }
}