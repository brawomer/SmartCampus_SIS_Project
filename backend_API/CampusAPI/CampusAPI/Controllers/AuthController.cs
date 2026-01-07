using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using CampusAPI.Data;
using CampusAPI.Models;

namespace CampusAPI.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AuthController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AuthController(AppDbContext context)
        {
            _context = context;
        }

        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {
            // 1. Search for the user in your SQL table
            var user = await _context.Users
                .FirstOrDefaultAsync(u => u.Username == request.Username && u.PasswordHash == request.Password);

            if (user == null)
            {
                return Unauthorized("Invalid username or password.");
            }

            // 2. If found, return their info to the App or Web
            return Ok(new
            {
                user.UserId,
                user.FullName,
                user.UserRole
            });
        }
    }

    // Helper class to receive data from Flutter/Web
    public class LoginRequest
    {
        public string Username { get; set; }
        public string Password { get; set; }
    }
}