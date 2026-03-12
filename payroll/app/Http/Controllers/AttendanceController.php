<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceLock;
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

        $isLocked = AttendanceLock::where('lock_date', $selectedDate)
                                  ->where('is_locked', true)
                                  ->exists();

        return view('attendance', compact('employees', 'loggedDates', 'selectedDate', 'search', 'isLocked'));
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

        $isLocked = AttendanceLock::where('lock_date', $validated['attendance_date'])->where('is_locked', true)->exists();
        if ($isLocked) {
            return redirect()->back()->withErrors(['error' => 'Attendance for this date is frozen and cannot be modified.']);
        }

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
        $skippedCount = 0;

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($header) {
                $header = false; 
                continue;
            }

            $empIdString = $row[0] ?? null;
            $date = $row[1] ?? null;
            $status = $row[2] ?? 'Present';
            $hoursWorked = $row[3] ?? ($status === 'Present' ? 8 : 0);
            $overtime = $row[4] ?? 0;

            if (!$empIdString || !$date) continue; 

            $formattedDate = date('Y-m-d', strtotime($date));

            $isLocked = AttendanceLock::where('lock_date', $formattedDate)->where('is_locked', true)->exists();
            if ($isLocked) {
                $skippedCount++;
                continue;
            }

            $employee = \App\Models\Employee::where('employee_id', $empIdString)->first();

            if ($employee) {
                \App\Models\Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'attendance_date' => $formattedDate,
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

        $message = "Biometrics imported! {$successCount} records updated.";
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} records skipped due to frozen dates).";
        }

        return back()->with('success', $message);
    }

    public function toggleLock(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $date = $request->input('date');

        $lock = AttendanceLock::firstOrCreate(['lock_date' => $date]);
        $lock->is_locked = !$lock->is_locked;
        $lock->save();

        $statusMessage = $lock->is_locked ? 'frozen securely' : 'unlocked and opened for edits';

        return redirect()->back()->with('success', "Attendance data for {$date} is now {$statusMessage}.");
    }
}