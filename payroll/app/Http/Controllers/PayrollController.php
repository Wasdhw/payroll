<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employee;
use App\Models\PayrollBatch;
use App\Models\PayrollItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $isFirstHalf = date('d') <= 15;
        $startDate = $isFirstHalf ? date('Y-m-01') : date('Y-m-16');
        $endDate = $isFirstHalf ? date('Y-m-15') : date('Y-m-t'); 

        $query = Employee::with(['attendances' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('attendance_date', [$startDate, $endDate]);
        }]);

        // Filter Logic
        if ($filter === 'permanent') {
            $query->where('employment_type', 'Permanent');
        } elseif ($filter === 'contractual') {
            $query->where('employment_type', 'Contractual');
        } elseif ($filter === 'daily') {
            $query->where('salary_type', 'Daily');
        } elseif ($filter === 'hourly') {
            $query->where('salary_type', 'Hourly');
        }

        // Search Logic
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%"); 
            });
        }

        $employees = $query->get();

        $payrollRecords = $employees->map(function ($employee) {
            return $this->calculatePayroll($employee);
        });

        return view('payroll.index', compact('payrollRecords', 'filter', 'search'));
    }

    public function store(Request $request)
    {
        $isFirstHalf = date('d') <= 15;
        $startDate = $isFirstHalf ? date('Y-m-01') : date('Y-m-16');
        $endDate = $isFirstHalf ? date('Y-m-15') : date('Y-m-t');

        $exists = PayrollBatch::where('period_start', $startDate)->where('period_end', $endDate)->exists();
        if ($exists) {
            return back()->with('error', 'Payroll for this period has already been saved.');
        }

        DB::beginTransaction();
        try {
            $batch = PayrollBatch::create([
                'batch_id' => 'PY-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))),
                'period_start' => $startDate,
                'period_end' => $endDate,
                'total_gross' => 0,
                'total_net' => 0,
                'processed_by' => auth()->user()->name,
            ]);

            $batchGross = 0; $batchNet = 0;
            $employees = Employee::all();

            foreach ($employees as $employee) {
                $calc = $this->calculatePayroll($employee, $startDate, $endDate);
                
                PayrollItem::create([
                    'payroll_batch_id' => $batch->id,
                    'employee_id' => $employee->id,
                    'basic_pay' => $calc->net_basic,
                    'additions' => $calc->additions,
                    'deductions' => $calc->total_deductions,
                    'net_pay' => $calc->net_pay,
                    'details' => json_encode([
                        'sss' => $calc->sss,
                        'philhealth' => $calc->philhealth,
                        'pagibig' => $calc->pagibig,
                        'absences' => $calc->days_absent
                    ])
                ]);

                $batchGross += $calc->gross_pay;
                $batchNet += $calc->net_pay;
            }

            $batch->update(['total_gross' => $batchGross, 'total_net' => $batchNet]);

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Batch ' . $batch->batch_id . ' saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function calculatePayroll($employee, $start = null, $end = null) {
        if (!$start) {
            $isFirstHalf = date('d') <= 15;
            $start = $isFirstHalf ? date('Y-m-01') : date('Y-m-16');
            $end = $isFirstHalf ? date('Y-m-15') : date('Y-m-t');
        }

        $attendances = $employee->attendances()->whereBetween('attendance_date', [$start, $end])->get();
        $daysPresent = $attendances->where('status', 'Present')->count();
        $daysAbsent = $attendances->where('status', 'Absent')->count();
        
        $grossBasic = 0; $absenceDeduction = 0; $netBasic = 0;

        if ($employee->salary_type === 'Daily') {
            $grossBasic = $employee->salary * $daysPresent;
            $netBasic = $grossBasic; 
        } elseif ($employee->salary_type === 'Hourly') {
            $grossBasic = $employee->salary * ($daysPresent * 8); 
            $netBasic = $grossBasic;
        } else {
            $grossBasic = $employee->salary / 2; 
            $estimatedDailyRate = $employee->salary / 22; 
            $absenceDeduction = $daysAbsent * $estimatedDailyRate;
            $netBasic = max(0, $grossBasic - $absenceDeduction);
        }
        
        $totalAdditions = $attendances->sum('allowance') + $attendances->sum('incentive');
        $grossPay = $netBasic + $totalAdditions;

        $sss = ($employee->salary * 0.045) / 2; 
        $philhealth = ($employee->salary * 0.025) / 2;
        $pagibig = 100.00; 
        $totalDeductions = $sss + $philhealth + $pagibig;
        
        $net_pay = $grossPay - $totalDeductions;

        return (object) [
            'employee' => $employee,
            'days_absent' => $daysAbsent,
            'gross_basic' => $grossBasic,
            'absence_deduction' => $absenceDeduction,
            'net_basic' => $netBasic,
            'additions' => $totalAdditions,
            'gross_pay' => $grossPay,
            'sss' => $sss,
            'philhealth' => $philhealth,
            'pagibig' => $pagibig,
            'total_deductions' => $totalDeductions,
            'net_pay' => $net_pay
        ];


    }

        public function history()
        {

        $batches = \App\Models\PayrollBatch::latest()->get();
        return view('payroll.history', compact('batches'));

        }

    public function show($id)
        {

            $batch = \App\Models\PayrollBatch::with('items.employee')->findOrFail($id);
            return view('payroll.show', compact('batch'));
            
        }

        public function downloadSlip($id)
        {
            $item = \App\Models\PayrollItem::with('employee', 'payrollBatch')->findOrFail($id);
    
            $details = json_decode($item->details);

            $pdf = Pdf::loadView('payroll.pdf-slip', compact('item', 'details'));

            return $pdf->download('Payslip_' . $item->employee->last_name . '_' . $item->payrollBatch->batch_id . '.pdf');
        }
        public function printBatch($id)
        {
            $batch = \App\Models\PayrollBatch::with('items.employee')->findOrFail($id);

            $data = [
                'batch' => $batch,
                'items' => $batch->items
            ];

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.pdf-batch', $data);

            $pdf->setPaper('a4', 'portrait');

            return $pdf->download('Full_Batch_' . $batch->batch_id . '.pdf');
        }

}