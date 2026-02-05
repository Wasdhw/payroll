<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // 1. Show the List of Employees
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    // 2. Show the "Add Employee" Form
    public function create()
    {
        return view('employees.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        // REQUIRED FIELDS (Will turn red if empty)
        'employee_id'            => 'required|unique:employees',
        'first_name'             => 'required|string',
        'last_name'              => 'required|string',
        'birth_date'             => 'required|date',
        'gender'                 => 'required',
        'civil_status'           => 'required',
        'address'                => 'required',
        'phone'                  => 'required',
        'email'                  => 'required|email|unique:employees',
        'department'             => 'required',
        'job_title'              => 'required',
        'employment_type'        => 'required',
        'join_date'              => 'required|date',
        'work_schedule'          => 'required',
        'salary'                 => 'required|numeric',
        'status'                 => 'required',
        'emergency_contact_name' => 'required|string',
        'emergency_contact_phone'=> 'required|string',
        'supervisor'             => 'required|string',
        'middle_name'            => 'nullable|string',

    ]);

    Employee::create($validated);

    return redirect()->route('employees.index')->with('success', 'Employee Record Created!');
}
}