<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        // Sample data for Thesis demonstration
        $attendance = [
            [
                'id' => 'EMP-001',
                'name' => 'Juan Dela Cruz',
                'date' => '2026-02-04',
                'status' => 'Present',
                'overtime' => 2, // hours
                'allowance' => 500.00,
                'incentive' => 200.00,
            ],
            [
                'id' => 'EMP-002',
                'name' => 'Maria Santos',
                'date' => '2026-02-04',
                'status' => 'Present',
                'overtime' => 0,
                'allowance' => 300.00,
                'incentive' => 0,
            ],
        ];

        return view('layouts.attendance', compact('attendance'));
    }
}