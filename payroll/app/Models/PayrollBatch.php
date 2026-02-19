<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollBatch extends Model
{
    protected $fillable = ['batch_id', 'period_start', 'period_end', 'total_gross', 'total_net', 'processed_by'];

    public function items() {
        return $this->hasMany(PayrollItem::class);
    }
}