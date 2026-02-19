<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

public function edit(Employee $employee)
    {
    return view('employees.edit', compact('employee'));
    }

public function update(Request $request, Employee $employee)
    {
    $validated = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|numeric',
        'salary' => 'required|numeric',
        'salary_type' => 'required|string',
        'address' => 'required',
        'department' => 'required',
        'job_title' => 'required',
        'salary' => 'required|numeric',
        'status' => 'required',
    ]);

    $employee->update($validated);

    return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
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