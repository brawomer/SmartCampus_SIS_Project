using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace CampusAPI.Models
{
    public class MaintenanceLog
    {
        [Key]
        public int LogId { get; set; }

        public int AssetId { get; set; }
        public int UserId { get; set; }

        // This links the log to the specific Asset
        [ForeignKey("AssetId")]
        public Asset? Asset { get; set; }

        public string Description { get; set; } = string.Empty;

        public DateTime ReportedDate { get; set; } = DateTime.Now;

        public bool IsFixed { get; set; } = false;


    }
}