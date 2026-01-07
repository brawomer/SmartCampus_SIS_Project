using Microsoft.EntityFrameworkCore;
using CampusAPI.Models;

namespace CampusAPI.Data
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { }

        public DbSet<User> Users { get; set; }
        // We will add Attendance and Assets here later
    }
}