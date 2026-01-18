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

<<<<<<< HEAD
builder.Services.AddControllers();
=======
// Add services to the container
builder.Services.AddControllers();

// ✅ ADD CORS
builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowFrontend",
        policy =>
        {
            policy
                .WithOrigins("http://127.0.0.1:5500")
                .AllowAnyHeader()
                .AllowAnyMethod();
        });
});

>>>>>>> a98b45e9c8d66643e8ff59c5b554a099800d1fba
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var app = builder.Build();

// Configure the HTTP request pipeline
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();

<<<<<<< HEAD
// --- ADD THIS LINE HERE ---
app.UseCors("AllowWeb"); 
=======
// ✅ USE CORS (MUST be before MapControllers)
app.UseCors("AllowFrontend");
>>>>>>> a98b45e9c8d66643e8ff59c5b554a099800d1fba

app.UseAuthorization();

app.MapControllers();

app.Run();