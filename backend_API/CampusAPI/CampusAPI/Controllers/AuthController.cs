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
        // POST: api/Auth/register
        [HttpPost("register")]
        public async Task<IActionResult> Register(User user)
        {
            // 1. Check if username already exists
            if (await _context.Users.AnyAsync(u => u.Username == user.Username))
                return BadRequest("Username already taken.");

            // 2. Add the user to the database
            _context.Users.Add(user);
            await _context.SaveChangesAsync();

            return Ok(new { Message = "Registration successful!" });
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