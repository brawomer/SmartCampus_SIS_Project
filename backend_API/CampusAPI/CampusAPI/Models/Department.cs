using System.ComponentModel.DataAnnotations;

namespace CampusAPI.Models
{
    public class Department
    {
        [Key]
        public int DeptId { get; set; }

        [Required]
        public string DeptName { get; set; } = string.Empty;
    }
}