<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ReportController;

// 1. Authentication Route (Open to public so users can log in)
Route::post('/login', [AuthController::class, 'login']);

// 2. Protected Routes (Only accessible with a valid Mobile Token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Get the profile of the logged-in student/teacher
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Handle Mobile QR Scans for Broken Items
    Route::post('/reports/scan', [ReportController::class, 'storeFromApp']);

    // Handle Mobile QR Scans for Classroom Attendance
    Route::post('/attendance/scan', [ReportController::class, 'scanAttendance']);
});