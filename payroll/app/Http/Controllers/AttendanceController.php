<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->query('date', date('Y-m-d'));
        $search = $request->query('search'); 
        
        $month = date('m', strtotime($selectedDate));
        $year = date('Y', strtotime($selectedDate));


        $query = Employee::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $query->where(function($q) use ($month, $year) {
            $q->where('status', '!=', 'Resigned') 
              ->orWhereHas('attendances', function($subQuery) use ($month, $year) {
                  $subQuery->whereMonth('attendance_date', $month)
                           ->whereYear('attendance_date', $year);
              });
        });

        $employees = $query->with(['attendances' => function($q) use ($month, $year) {
                $q->whereMonth('attendance_date', $month)
                  ->whereYear('attendance_date', $year);
            }])
            ->latest()
            ->get();

        $loggedDates = Attendance::whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get()
            ->pluck('attendance_date')
            ->map(fn($date) => date('Y-m-d', strtotime($date))) 
            ->unique()
            ->toArray();

        return view('attendance', compact('employees', 'loggedDates', 'selectedDate', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|string',
            'hours_worked' => 'nullable|numeric|min:0|max:24', 
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
                'hours_worked' => $validated['hours_worked'] ?? ($validated['status'] === 'Present' ? 8 : 0), 
                'overtime_hours' => $validated['overtime_hours'] ?? 0,
                'allowance' => $validated['allowance'] ?? 0,
                'incentive' => $validated['incentive'] ?? 0,
            ]
        );

        return redirect()->route('attendance.index', ['date' => $validated['attendance_date']])
                         ->with('success', 'Attendance logged successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'biometric_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('biometric_file');
        $handle = fopen($file->getPathname(), "r");
        
        $header = true;
        $successCount = 0;

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($header) {
                $header = false; 
                continue;
            }

            // 0: Employee ID 
            // 1: Date 
            // 2: Status 
            // 3: Hours Worked 
            // 4: Overtime Hours 

            $empIdString = $row[0] ?? null;
            $date = $row[1] ?? null;
            $status = $row[2] ?? 'Present';
            $hoursWorked = $row[3] ?? ($status === 'Present' ? 8 : 0);
            $overtime = $row[4] ?? 0;

            if (!$empIdString || !$date) continue; 

            $employee = \App\Models\Employee::where('employee_id', $empIdString)->first();

            if ($employee) {
                \App\Models\Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'attendance_date' => date('Y-m-d', strtotime($date)),
                    ],
                    [
                        'status' => ucfirst($status),
                        'hours_worked' => is_numeric($hoursWorked) ? $hoursWorked : 0, 
                        'overtime_hours' => is_numeric($overtime) ? $overtime : 0,
                    ]
                );
                $successCount++;
            }
        }

        fclose($handle);

        return back()->with('success', "Biometrics imported successfully! {$successCount} records updated.");
    }
}