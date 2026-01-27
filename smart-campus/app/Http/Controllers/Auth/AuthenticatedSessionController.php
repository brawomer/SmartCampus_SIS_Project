<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    // This shows your login page
    public function create()
    {
        return view('pages.auth.login');
    }

    // This handles the "Login" button click
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

     return match($role) {
    'admin'      => redirect()->route('admin.dashboard'),
    'teacher'    => redirect()->route('teacher.dashboard'),
    'technician' => redirect()->route('technician.dashboard'),
    'staff'      => redirect()->route('department.index'), // Your Nursing page
    'marketing'  => redirect()->route('marketing.dashboard'),
    default      => redirect()->route('dashboard'), // Students
     };
            //
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
