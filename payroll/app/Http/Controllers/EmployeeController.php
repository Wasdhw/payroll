<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        
        $employees = [
            [
                'id' => 'EMP-001',
                'name' => 'Juan Dela Cruz',
                'position' => 'Instructor I',
                'department' => 'College of Arts',
                'status' => 'Active',
                'date_hired' => '2023-08-15',
            ],
            [
                'id' => 'EMP-002',
                'name' => 'Maria Santos',
                'position' => 'Registrar Staff',
                'department' => 'Admin Office',
                'status' => 'Active',
                'date_hired' => '2024-01-10',
            ],
            [
                'id' => 'EMP-003',
                'name' => 'Pedro Penduko',
                'position' => 'Maintenance',
                'department' => 'Facilities',
                'status' => 'On Leave',
                'date_hired' => '2022-03-20',
            ],
        ];

        return view('layouts.employees', compact('employees'));
    }
}