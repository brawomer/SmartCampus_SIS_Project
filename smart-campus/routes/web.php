<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Use a single route to sort everyone out
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'staff') {
        return redirect()->route('department.index');
    } elseif ($role === 'teacher') {
        return view('teacher.dashboard');
    } elseif ($role === 'technician') {
        return view('technician.dashboard');
    }

    return view('dashboard'); // Default for students
})->middleware(['auth'])->name('dashboard');

// This defines the "login.store" route your form is looking for
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
// Teacher Routes
Route::middleware(['auth', 'checkRole:teacher'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');
});

// Technician Routes
Route::middleware(['auth', 'checkRole:technician'])->group(function () {
    Route::get('/technician/dashboard', function () {
        return view('technician.dashboard');
    })->name('technician.dashboard');
});

// Admin Routes
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::get('/department/export', [DepartmentController::class, 'exportStudents'])->name('department.export');

// Admin / Head of Campus Routes
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', [ReportController::class, 'adminDashboard'])->name('admin.dashboard');
});

// Marketing / Purchasing Routes
Route::middleware(['auth', 'checkRole:marketing'])->group(function () {
    Route::get('/marketing/purchases', function() {
        return view('marketing.dashboard'); // You can build this next!
    })->name('marketing.dashboard');
});

Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::middleware(['auth', 'checkRole:technician'])->group(function () {
    Route::get('/technician/dashboard', [ReportController::class, 'technicianIndex'])->name('technician.index');
    Route::patch('/reports/{id}/fix', [ReportController::class, 'markAsFixed'])->name('reports.fix');
});

Route::middleware(['auth', 'checkRole:staff'])->group(function () {
    Route::post('/department/add-student', [DepartmentController::class, 'storeStudent'])->name('department.addStudent');
    Route::delete('/department/student/{user}', [DepartmentController::class, 'destroyStudent'])->name('department.deleteStudent');
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');
// Only Technicians can see this page
Route::get('/technician/purchase-requests', function () {
    return view('technician.purchases');
})->middleware('checkRole:technician');

// Only Marketing can see this page
Route::get('/marketing/invoices', function () {
    return view('marketing.invoices');
})->middleware('checkRole:marketing');

// Only Staff (Heads of Dept) can create accounts
Route::get('/staff/create-account', function () {
    return view('staff.create');
})->middleware('checkRole:staff');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
