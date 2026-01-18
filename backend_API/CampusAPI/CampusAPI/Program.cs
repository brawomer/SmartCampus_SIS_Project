using Microsoft.EntityFrameworkCore;

var builder = WebApplication.CreateBuilder(args);
// Add services to the container.

builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowWeb",
        policy =>
        {
            policy.AllowAnyOrigin() // This allows VS Code to connect
                  .AllowAnyMethod()
                  .AllowAnyHeader();
        });
});
builder.Services.AddDbContext<CampusAPI.Data.AppDbContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("DefaultConnection")));

builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();

// --- ADD THIS LINE HERE ---
app.UseCors("AllowWeb"); 

app.UseAuthorization();

app.MapControllers();

app.Run();