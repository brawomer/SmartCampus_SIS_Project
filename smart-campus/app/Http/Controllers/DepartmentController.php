<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentController extends Controller
{
    public function exportStudents()
{
    $fileName = auth()->user()->department . '_Students.xlsx';
    return Excel::download(new StudentsExport, $fileName);
}

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
    // 1. Validate the input (Good for security!)
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8', // Ensure password is at least 8 characters
    ]);

    // 2. Create the student using the form data
    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password), // Use the input password
        'role' => 'student',
        'department' => auth()->user()->department,
    ]);

    return back()->with('success', 'Student account created successfully!');
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
public function saveGrade(Request $request)
{
    \App\Models\Grade::create([
        'student_id' => $request->student_id,
        'teacher_id' => auth()->id(),
        'subject' => $request->subject,
        'score' => $request->score,
    ]);

    return back()->with('success', 'Grade assigned successfully!');
}
}
