using CampusAPI.Data;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

[Route("api/[controller]")]
[ApiController]
public class AssetsController : ControllerBase
{
    private readonly AppDbContext _context;
    public AssetsController(AppDbContext context) { _context = context; }

    [HttpGet("scan/{assetTag}")]
    public async Task<IActionResult> ScanAsset(string assetTag)
    {
        var asset = await _context.Assets
    .FirstOrDefaultAsync(a => a.QRCodeUID == assetTag);

        if (asset == null) return NotFound("Asset not found in system.");
        return Ok(asset);
    }
}