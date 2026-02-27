<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Employee::query();

        // Search by Name or ID
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by Status (Active, Resigned, etc.)
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $employees = $query->latest()->get();

        return view('employees.index', compact('employees', 'search', 'status'));
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
            'employee_id'             => 'required|unique:employees,employee_id,' . $employee->id,
            'first_name'              => 'required|string',
            'middle_name'             => 'nullable|string',
            'last_name'               => 'required|string',
            'birth_date'              => 'required|date',
            'gender'                  => 'required',
            'civil_status'            => 'required',
            'address'                 => 'required',
            'phone'                   => 'required|digits:11', 
            'email'                   => 'required|email|unique:employees,email,' . $employee->id,
            'department'              => 'required',
            'job_title'               => 'required',
            'employment_type'         => 'required',
            'join_date'               => 'required|date',
            'work_schedule'           => 'required',
            'salary'                  => 'required|numeric',
            'salary_type'             => 'required|string',
            'status'                  => 'required',
            'emergency_contact_name'  => 'required|string',
            'emergency_contact_phone' => 'required|digits:11',
            'supervisor'              => 'required|string',
        ], [

            'phone.digits' => 'The contact number must be exactly 11 digits (e.g. 09123456789).',
            'emergency_contact_phone.digits' => 'The emergency contact number must be exactly 11 digits.',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'             => 'required|unique:employees',
            'first_name'              => 'required|string',
            'middle_name'             => 'nullable|string',
            'last_name'               => 'required|string',
            'birth_date'              => 'required|date',
            'gender'                  => 'required',
            'civil_status'            => 'required',
            'address'                 => 'required',
            'phone'                   => 'required|digits:11',
            'email'                   => 'required|email|unique:employees',
            'department'              => 'required',
            'job_title'               => 'required',
            'employment_type'         => 'required',
            'join_date'               => 'required|date',
            'work_schedule'           => 'required',
            'salary'                  => 'required|numeric',
            'salary_type'             => 'required|string',
            'status'                  => 'required',
            'emergency_contact_name'  => 'required|string',
            'emergency_contact_phone' => 'required|digits:11',
            'supervisor'              => 'required|string',
        ], [

            'phone.digits' => 'The contact number must be exactly 11 digits (e.g. 09123456789).',
            'emergency_contact_phone.digits' => 'The emergency contact number must be exactly 11 digits.',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee Record Created!');
    }
}