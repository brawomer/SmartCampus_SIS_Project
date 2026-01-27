<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection
{
    public function collection()
    {
        // Only get students in the same department as the logged-in Head Teacher
        return User::where('role', 'student')
                   ->where('department', auth()->user()->department)
                   ->select('name', 'email', 'created_at')
                   ->get();
    }
}
