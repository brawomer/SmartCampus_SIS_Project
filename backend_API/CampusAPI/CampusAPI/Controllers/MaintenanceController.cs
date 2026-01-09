using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusAPI.Data;
using CampusAPI.Models;

namespace CampusAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class MaintenanceController : ControllerBase
    {
        private readonly AppDbContext _context;

        public MaintenanceController(AppDbContext context)
        {
            _context = context;
        }

        // POST: api/Maintenance/report
        [HttpPost("report")]
        public async Task<IActionResult> ReportProblem(int assetId, string description, int userId)
        {
            var asset = await _context.Assets.FindAsync(assetId);
            if (asset == null) return NotFound("Asset not found.");

            var log = new MaintenanceLog
            {
                AssetId = assetId,
                Description = description,
                UserId = userId,
                ReportedDate = DateTime.Now,
                IsFixed = false
            };

            asset.Status = "Broken";
            _context.MaintenanceLogs.Add(log);
            await _context.SaveChangesAsync();

            return Ok(new { Message = "Asset marked as Broken." });
        }

        // POST: api/Maintenance/fix/{assetId}
        [HttpPost("fix/{assetId}")]
        public async Task<IActionResult> MarkAsFixed(int assetId)
        {
            var asset = await _context.Assets.FindAsync(assetId);
            if (asset == null) return NotFound("Asset not found.");

            asset.Status = "Functional";

            var logs = await _context.MaintenanceLogs
                .Where(l => l.AssetId == assetId && l.IsFixed == false)
                .ToListAsync();

            foreach (var log in logs)
            {
                log.IsFixed = true;
            }

            await _context.SaveChangesAsync();
            return Ok(new { Message = $"{asset.AssetName} is now Functional." });
        }
    }
}