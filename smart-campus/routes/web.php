<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;

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
