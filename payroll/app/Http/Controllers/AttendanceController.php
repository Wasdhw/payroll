<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get the selected date from the URL (defaults to today if no date is clicked)
        $selectedDate = $request->query('date', date('Y-m-d'));

        // 2. Fetch employees and ONLY load their attendance for the chosen date
        $employees = Employee::with(['attendances' => function($query) use ($selectedDate) {
            $query->where('attendance_date', $selectedDate);
        }])->latest()->get();

        // 3. Fetch logs for the current month being viewed (to power the green dots)
        $loggedDates = Attendance::whereMonth('attendance_date', date('m', strtotime($selectedDate)))
            ->whereYear('attendance_date', date('Y', strtotime($selectedDate)))
            ->get()
            ->pluck('attendance_date')
            ->map(fn($date) => date('Y-m-d', strtotime($date))) // Format cleanly
            ->unique()
            ->toArray();

        // Pass all variables to the view
        return view('attendance', compact('employees', 'loggedDates', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|string',
            'overtime_hours' => 'nullable|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'incentive' => 'nullable|numeric|min:0',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            [
                'status' => $validated['status'],
                'overtime_hours' => $validated['overtime_hours'] ?? 0,
                'allowance' => $validated['allowance'] ?? 0,
                'incentive' => $validated['incentive'] ?? 0,
            ]
        );

        // SMART REDIRECT: Redirect back to the exact date they just logged!
        return redirect()->route('attendance.index', ['date' => $validated['attendance_date']])
                         ->with('success', 'Attendance logged successfully!');
    }
}