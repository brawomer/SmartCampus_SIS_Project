using System.ComponentModel.DataAnnotations;

public class User
{
    [Key]
    public int UserId { get; set; }

    [Required]
    public string Username { get; set; }

    [Required]
    public string PasswordHash { get; set; } // Matches your SQL column name

    [Required]
    public string FullName { get; set; }

    [Required]
    public string Email { get; set; }

    [Required]
    public string UserRole { get; set; } = "Student";

    public int? DeptId { get; set; } // Keep this nullable if some users don't have a department
}