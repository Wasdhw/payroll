<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $newEmployees = Employee::where('created_at', '>=', now()->subDays(7))->count();

        $isFirstHalf = date('d') <= 15;
        $startDate = $isFirstHalf ? date('Y-m-01') : date('Y-m-16');
        $endDate = $isFirstHalf ? date('Y-m-15') : date('Y-m-t'); 

        $employees = Employee::all();
        $semiMonthlyBasic = $employees->sum('salary') / 2;

        $additions = Attendance::whereBetween('attendance_date', [$startDate, $endDate])
            ->get()
            ->sum(function ($log) {
                return $log->allowance + $log->incentive;
            });

        $totalPayroll = $semiMonthlyBasic + $additions;

        return view('dashboard', compact(
            'totalEmployees', 
            'newEmployees', 
            'totalPayroll', 
            'isFirstHalf'
        ));
    }
}