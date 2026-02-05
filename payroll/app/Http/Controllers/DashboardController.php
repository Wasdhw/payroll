<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $totalEmployees = Employee::count();

        $newEmployees = Employee::where('created_at', '>=', now()->subDays(7))->count();

        $totalPayroll = Employee::where('status', 'Active')->sum('salary');

        return view('dashboard', compact('totalEmployees', 'newEmployees', 'totalPayroll'));
    }
}