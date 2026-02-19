<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'civil_status',
        'address',
        'phone',
        'email',
        'salary_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'department',
        'job_title',
        'employment_type',
        'join_date',
        'work_schedule',
        'salary',
        'status',
        'supervisor',
    ];
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}