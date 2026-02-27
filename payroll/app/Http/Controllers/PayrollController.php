<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employee;
use App\Models\PayrollBatch;
use App\Models\PayrollItem;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $this->autoProcessPreviousPeriod();

        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $isFirstHalf = date('d') <= 15;
        $startDate = $isFirstHalf ? date('Y-m-01') : date('Y-m-16');
        $endDate = $isFirstHalf ? date('Y-m-15') : date('Y-m-t'); 

        $isProcessed = PayrollBatch::where('period_start', $startDate)
                                   ->where('period_end', $endDate)
                                   ->exists();

        $query = Employee::with(['attendances' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('attendance_date', [$startDate, $endDate]);
        }]);

        if (in_array($filter, ['permanent', 'contractual'])) {
            $query->where('employment_type', ucfirst($filter));
        } elseif (in_array($filter, ['daily', 'hourly'])) {
            $query->where('salary_type', ucfirst($filter));
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%"); 
            });
        }

        $employees = $query->get();

        $payrollRecords = $employees->map(function ($employee) use ($startDate, $endDate) {
            return $this->calculatePayroll($employee, $startDate, $endDate, false);
        });

        return view('payroll.index', compact('payrollRecords', 'filter', 'search', 'isProcessed', 'startDate', 'endDate'));
    }

    public function store(Request $request)
    {
        $isFirstHalf = date('d') <= 15;
        $startDate = $isFirstHalf ? date('Y-m-01') : date('Y-m-16');
        $endDate = $isFirstHalf ? date('Y-m-15') : date('Y-m-t');

        if (PayrollBatch::where('period_start', $startDate)->where('period_end', $endDate)->exists()) {
            return back()->with('error', 'Payroll for this period has already been saved.');
        }

        $this->generateBatch($startDate, $endDate, auth()->user()->name ?? 'System Admin');

        return redirect()->route('payroll.history')->with('success', "Batch saved successfully.");
    }

    public function show($id)
    {
        $batch = PayrollBatch::with('items.employee')->findOrFail($id);
        return view('payroll.show', compact('batch'));
    }

    public function finalize($id)
    {
        $batch = PayrollBatch::findOrFail($id);
        
        $batch->status = 'Paid';
        $batch->save(); 

        $batch->items()->update(['is_paid' => true]);

        return redirect()->route('payroll.show', $id)->with('success', 'Batch Finalized!');    
    }

    public function printBatch($id)
    {
        $batch = PayrollBatch::with(['items.employee'])->findOrFail($id);
        
        DB::transaction(function() use ($batch) {
            $batch->update(['status' => 'Paid']);
            $batch->items()->update(['is_paid' => true]);
        });

        $data = [
            'batch' => $batch,
            'items' => $batch->items
        ];

        $pdf = Pdf::loadView('payroll.pdf-batch', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download("Full_Batch_{$batch->batch_id}.pdf");
    }

    public function downloadSlip($id)
    {
        $item = PayrollItem::with('employee', 'payrollBatch')->findOrFail($id);
        $item->update(['is_paid' => true]);

        $batch = $item->payrollBatch;
        if (!$batch->items()->where('is_paid', false)->exists()) {
            $batch->update(['status' => 'Paid']);
        }

        $details = json_decode($item->details);
        $pdf = Pdf::loadView('payroll.pdf-slip', compact('item', 'details'));
        $pdf->setPaper('a5', 'landscape'); 

        return $pdf->download("Payslip_{$item->employee->last_name}.pdf");
    }

    public function history()
    {
        $batches = PayrollBatch::latest()->get();
        return view('payroll.history', compact('batches'));
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    private function autoProcessPreviousPeriod()
    {
        $today = Carbon::today();
        
        if ($today->day <= 15) {
            $prevStart = $today->copy()->subMonth()->startOfMonth()->addDays(15); 
            $prevEnd = $today->copy()->subMonth()->endOfMonth(); 
        } else {
            $prevStart = $today->copy()->startOfMonth(); 
            $prevEnd = $today->copy()->startOfMonth()->addDays(14); 
        }

        $exists = PayrollBatch::where('period_start', $prevStart->format('Y-m-d'))
                              ->where('period_end', $prevEnd->format('Y-m-d'))
                              ->exists();

        if (!$exists) {
            $this->generateBatch($prevStart->format('Y-m-d'), $prevEnd->format('Y-m-d'), 'System Auto');
        }
    }

    private function generateBatch($startDate, $endDate, $processedBy)
    {
        DB::transaction(function () use ($startDate, $endDate, $processedBy) {
            $batch = PayrollBatch::create([
                'batch_id' => 'PY-' . date('Ymd', strtotime($endDate)) . '-' . strtoupper(bin2hex(random_bytes(2))),
                'period_start' => $startDate,
                'period_end' => $endDate,
                'total_gross' => 0,
                'total_net' => 0,
                'status' => 'Pending',
                'processed_by' => $processedBy,
            ]);

            $batchGross = 0; 
            $batchNet = 0;
            $employees = Employee::all();

            foreach ($employees as $employee) {

                $calc = $this->calculatePayroll($employee, $startDate, $endDate, true);
                
                PayrollItem::create([
                    'payroll_batch_id' => $batch->id,
                    'employee_id' => $employee->id,
                    'basic_pay' => $calc->net_basic,
                    'additions' => $calc->additions,
                    'deductions' => $calc->total_deductions,
                    'net_pay' => $calc->net_pay,
                    'is_paid' => false,
                    'details' => json_encode([
                        'sss' => $calc->sss,
                        'philhealth' => $calc->philhealth,
                        'pagibig' => $calc->pagibig,
                        'tax' => $calc->tax,
                        'absences' => $calc->days_absent,
                        'absence_deduction' => $calc->absence_deduction, 
                        'gross_basic' => $calc->gross_basic,             
                        'total_hours' => $calc->total_hours              
                    ])
                ]);

                $batchGross += $calc->gross_pay;
                $batchNet += $calc->net_pay;
            }

            $batch->update(['total_gross' => $batchGross, 'total_net' => $batchNet]);
        });
    }

private function calculatePayroll($employee, $start = null, $end = null, $isSavingBatch = false) {
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        $attendances = $employee->attendances()
                                ->whereBetween('attendance_date', [$startDate, $endDate])
                                ->get();

        $daysPresent = 0;
        $daysAbsent = 0;
        $totalHours = 0;
        $totalAdditions = 0;

        $checkUntil = $endDate->isFuture() ? Carbon::today() : $endDate;

        for ($date = $startDate->copy(); $date->lte($checkUntil); $date->addDay()) {
            
            if ($date->isSunday()) { 
                continue; 
            }

            $log = $attendances->firstWhere('attendance_date', $date->format('Y-m-d'));

            if (!$log) {

                if ($isSavingBatch) {
                    $daysAbsent++; 
                    
                    Attendance::firstOrCreate([
                        'employee_id' => $employee->id,
                        'attendance_date' => $date->format('Y-m-d')
                    ], [
                        'status' => 'Absent',
                        'hours_worked' => 0,
                        'overtime_hours' => 0,
                        'allowance' => 0,
                        'incentive' => 0
                    ]);
                }
            } elseif ($log->status === 'Absent') {
                $daysAbsent++;
            } elseif ($log->status === 'Present') {
                $daysPresent++;
                $totalHours += $log->hours_worked;
                $totalAdditions += $log->allowance + $log->incentive;
            }
        }

        $grossBasic = 0; $absenceDeduction = 0; $netBasic = 0;

        if ($employee->salary_type === 'Daily') {
            $grossBasic = $employee->salary * $daysPresent;
            $netBasic = $grossBasic; 
        } elseif ($employee->salary_type === 'Hourly') {
            $grossBasic = $employee->salary * $totalHours; 
            $netBasic = $grossBasic;
        } else {
            $grossBasic = $employee->salary / 2; 
            $dailyRate = $employee->salary / 26; 
            $absenceDeduction = $daysAbsent * $dailyRate;
            $netBasic = max(0, $grossBasic - $absenceDeduction);
        }
        
        $grossPay = $netBasic + $totalAdditions;

        // Deductions
        $sss = ($employee->salary * 0.045) / 2; 
        $philhealth = ($employee->salary * 0.02) / 2;
        $pagibig = 100.00; 
        $mandatoryDeductions = $sss + $philhealth + $pagibig;
        
        $taxableIncome = max(0, $grossPay - $mandatoryDeductions);
        $tax = 0;

        if ($taxableIncome > 333333) { $tax = 91770.70 + (($taxableIncome - 333333) * 0.35); } 
        elseif ($taxableIncome > 83333) { $tax = 16770.70 + (($taxableIncome - 83333) * 0.30); } 
        elseif ($taxableIncome > 33333) { $tax = 4270.70 + (($taxableIncome - 33333) * 0.25); } 
        elseif ($taxableIncome > 16667) { $tax = 937.50 + (($taxableIncome - 16667) * 0.20); } 
        elseif ($taxableIncome > 10417) { $tax = (($taxableIncome - 10417) * 0.15); }

        $totalDeductions = $mandatoryDeductions + max(0, $tax);
        $net_pay = $grossPay - $totalDeductions;

        return (object) [
            'employee' => $employee,
            'days_absent' => $daysAbsent,
            'total_hours' => $totalHours,
            'gross_basic' => $grossBasic,
            'absence_deduction' => $absenceDeduction,
            'net_basic' => $netBasic,
            'additions' => $totalAdditions,
            'gross_pay' => $grossPay,
            'sss' => $sss,
            'philhealth' => $philhealth,
            'pagibig' => $pagibig,
            'tax' => $tax,
            'total_deductions' => $totalDeductions,
            'net_pay' => $net_pay
        ];
    }
}