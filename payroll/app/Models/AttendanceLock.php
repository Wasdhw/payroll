<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLock extends Model
{
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lock_date',
        'is_locked',
    ];
}
