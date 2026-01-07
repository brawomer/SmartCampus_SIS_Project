-- 1. Create the Database
CREATE DATABASE SmartCampusDB;
GO
USE SmartCampusDB;
GO

-- 2. Core User & Academic Tables
CREATE TABLE Departments (
    DeptId INT PRIMARY KEY IDENTITY(1,1),
    DeptName NVARCHAR(100) NOT NULL
);

CREATE TABLE Users (
    UserId INT PRIMARY KEY IDENTITY(1,1),
    Username NVARCHAR(50) UNIQUE NOT NULL,
    PasswordHash NVARCHAR(MAX) NOT NULL, -- Never store plain text passwords!
    FullName NVARCHAR(100),
    UserRole NVARCHAR(20), -- 'Student', 'Teacher', 'Technician', 'Marketing', 'Head'
    DeptId INT FOREIGN KEY REFERENCES Departments(DeptId)
);

CREATE TABLE Courses (
    CourseId INT PRIMARY KEY IDENTITY(1,1),
    CourseName NVARCHAR(100) NOT NULL,
    CourseCode NVARCHAR(20) UNIQUE,
    DeptId INT FOREIGN KEY REFERENCES Departments(DeptId)
);

CREATE TABLE Enrollments (
    EnrollmentId INT PRIMARY KEY IDENTITY(1,1),
    StudentId INT FOREIGN KEY REFERENCES Users(UserId),
    CourseId INT FOREIGN KEY REFERENCES Courses(CourseId),
    EnrollmentDate DATETIME DEFAULT GETDATE(),
    Grade NVARCHAR(5) -- e.g., 'A', 'B+'
);

-- 3. Smart Campus (Asset & Reporting) Tables
CREATE TABLE Rooms (
    RoomId INT PRIMARY KEY IDENTITY(1,1),
    RoomNumber NVARCHAR(20) NOT NULL,
    BuildingName NVARCHAR(50)
);

CREATE TABLE Assets (
    AssetId INT PRIMARY KEY IDENTITY(1,1),
    AssetName NVARCHAR(100) NOT NULL,
    AssetTag NVARCHAR(50) UNIQUE, -- This is for your QR Code
    RoomId INT FOREIGN KEY REFERENCES Rooms(RoomId),
    Status NVARCHAR(20) DEFAULT 'Functional' -- 'Functional', 'Broken', 'Under Repair'
);

CREATE TABLE MaintenanceReports (
    ReportId INT PRIMARY KEY IDENTITY(1,1),
    AssetId INT FOREIGN KEY REFERENCES Assets(AssetId),
    ReportedBy INT FOREIGN KEY REFERENCES Users(UserId),
    Description NVARCHAR(MAX),
    Status NVARCHAR(20) DEFAULT 'Pending', -- 'Pending', 'In Progress', 'Fixed'
    ReportDate DATETIME DEFAULT GETDATE()
);

CREATE TABLE PurchaseRequests (
    RequestId INT PRIMARY KEY IDENTITY(1,1),
    RequestedBy INT FOREIGN KEY REFERENCES Users(UserId),
    ItemName NVARCHAR(100),
    Quantity INT,
    EstimatedPrice DECIMAL(18,2),
    ApprovalStatus NVARCHAR(20) DEFAULT 'Pending', -- 'Pending', 'Approved', 'Rejected'
    RequestDate DATETIME DEFAULT GETDATE()
);

CREATE TABLE Attendance (
    AttendanceId INT PRIMARY KEY IDENTITY(1,1),
    EnrollmentId INT FOREIGN KEY REFERENCES Enrollments(EnrollmentId),
    AttendanceDate DATETIME DEFAULT GETDATE(),
    Status NVARCHAR(20), -- 'Present', 'Late', 'Absent'
    MinutesLate INT DEFAULT 0
);

USE master;
GO

-- 1. Only create the database if it DOES NOT exist
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'SmartCampusDB')
BEGIN
    CREATE DATABASE SmartCampusDB;
END
GO

USE SmartCampusDB;
GO

-- 2. Only create tables if they DO NOT exist
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Departments]') AND type in (N'U'))
BEGIN
    CREATE TABLE Departments (
        DeptId INT PRIMARY KEY IDENTITY(1,1),
        DeptName NVARCHAR(100) NOT NULL
    );
END

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Rooms]') AND type in (N'U'))
BEGIN
    CREATE TABLE Rooms (
        RoomId INT PRIMARY KEY IDENTITY(1,1),
        RoomNumber NVARCHAR(20) NOT NULL,
        BuildingName NVARCHAR(50)
    );
END
-- (And so on for other tables...)