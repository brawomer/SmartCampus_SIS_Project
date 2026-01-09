using System.ComponentModel.DataAnnotations;

namespace CampusAPI.Models
{
    public class PurchaseRequest
    {
        [Key]
        public int RequestId { get; set; }

        [Required]
        public string ItemName { get; set; } = string.Empty;

        public int Quantity { get; set; } = 1;

        public int RequestedBy { get; set; } // Links to UserId

        public string RequestStatus { get; set; } = "Pending"; // Pending, Approved, Rejected

        public DateTime RequestDate { get; set; } = DateTime.Now;
    }
}