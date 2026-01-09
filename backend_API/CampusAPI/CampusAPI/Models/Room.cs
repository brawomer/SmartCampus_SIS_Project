using System.ComponentModel.DataAnnotations;

namespace CampusAPI.Models
{
    public class Room
    {
        [Key]
        public int RoomId { get; set; }

        [Required]
        public string RoomNumber { get; set; } = string.Empty;

        public string? BuildingName { get; set; }
    }
}