<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
public function index(): View
{
    /** @var \App\Models\User $user */
    $user = auth()->user();

    // The red line should disappear now because we told VS Code $user is a Model
    $myDept = $user->department;

    $staff = User::where('department', $myDept)->get();

    return view('department.index', compact('staff'));
}

    public function storeStudent(Request $request)
{
    // 1. Create the student
    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make('password123'), // Default password
        'role' => 'student',
        'department' => auth()->user()->department, // Automatically assign to the same department
    ]);

    return back()->with('success', 'Student added successfully!');
}
public function destroyStudent(User $user): RedirectResponse
{
    // Safety check: Only delete if they are in your department
    if ($user->department === auth()->user()->department) {
        $user->delete();
        return back()->with('success', 'Student removed successfully.');
    }
    
    return back()->with('error', 'You cannot delete students from other departments.');
}
}
