<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_batch_id', 
        'employee_id', 
        'basic_pay', 
        'additions', 
        'deductions', 
        'net_pay', 
        'details'
    ];

    // THIS IS THE MISSING LINK!
    public function payrollBatch()
    {
        return $this->belongsTo(PayrollBatch::class, 'payroll_batch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}