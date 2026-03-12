<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $query = Employee::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $employees = $query->latest()->get();
        return view('employees.index', compact('employees', 'search', 'status'));
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function store(Request $request)
    {
    if ($request->has('salary')) {
        $request->merge([
            'salary' => str_replace(',', '', $request->salary)
        ]);
    }

    $validated = $this->validateEmployee($request);

    Employee::create($validated);

    return redirect()->route('employees.index')
        ->with('success', 'Employee Record Created!');
    }

    public function update(Request $request, Employee $employee)
    {
        if ($request->has('salary')) {
            $request->merge(['salary' => str_replace(',', '', $request->salary)]);
        }

        $validated = $this->validateEmployee($request, $employee->id);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    protected function validateEmployee(Request $request, $id = null)
    {
        return $request->validate([
            'employee_id'     => 'required|unique:employees,employee_id,' . $id,
            'first_name'      => 'required|string',
            'middle_name'     => 'nullable|string',
            'last_name'       => 'required|string',
            'birth_date'      => 'required|date',
            'gender'          => 'required',
            'civil_status'    => 'required',
            'address'         => 'required',
            'phone'           => 'required|digits:11',
            'email'           => 'required|email|unique:employees,email,' . $id,
            'department'      => 'required',
            'job_title'       => 'required',
            'employment_type' => ['required', Rule::in(['Regular', 'Probationary', 'Contractual', 'Part-Time'])],
            'join_date'       => 'required|date',
            'work_schedule'   => ['required', Rule::in(['Daily', 'Hourly'])],
            'salary'          => 'required|numeric',
            'salary_type'     => ['required', Rule::in(['Monthly', 'Daily', 'Hourly', 'Daily Rate', 'Hourly Rate'])],
            'status'          => 'required',
            'supervisor'      => 'required|string',
        ]);
    }
}