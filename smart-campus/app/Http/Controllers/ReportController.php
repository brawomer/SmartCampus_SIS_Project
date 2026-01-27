<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;namespace App\Http\Controllers;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function storeFromApp(Request $request)
{
    // 1. Validate the data from the app scan
    $request->validate([
        'item_id' => 'required', // The ID from the QR code
        'description' => 'required|string',
        'user_id' => 'required' // The ID of the student/teacher scanning
    ]);

    // 2. Create the report
    $report = \App\Models\Report::create([
        'user_id' => $request->user_id,
        'item_name' => "Scanned Item ID: " . $request->item_id,
        'description' => $request->description,
        'status' => 'pending',
    ]);

    // 3. Send a message back to the Mobile App
    return response()->json([
        'message' => 'Report received! A technician will check it soon.',
        'report_id' => $report->id
    ], 201);
}
// Show all pending reports for the technician
public function technicianIndex()
{
    $reports = \App\Models\Report::where('status', 'pending')->get();
    return view('technician.index', compact('reports'));
}

// Mark a report as fixed
public function markAsFixed($id)
{
    $report = \App\Models\Report::findOrFail($id);
    $report->update(['status' => 'fixed']);

    return back()->with('success', 'Item marked as fixed!');
}
public function adminDashboard()
{
    // Count how many items are currently broken vs fixed
    $stats = [
        'pending' => \App\Models\Report::where('status', 'pending')->count(),
        'fixed'   => \App\Models\Report::where('status', 'fixed')->count(),
        'total'   => \App\Models\Report::count(),
    ];

    // Get the latest 5 reports to show on the dashboard
    $latestReports = \App\Models\Report::latest()->take(5)->get();

    return view('admin.dashboard', compact('stats', 'latestReports'));
}
public function scanAttendance(Request $request)
{
    // Record that the student is in the room
    \App\Models\Attendance::create([
        'user_id' => auth()->id(),
        'room_name' => $request->room_id, // The QR code contains the Room ID
    ]);

    return response()->json(['message' => 'Attendance recorded for ' . $request->room_id]);
}
}
