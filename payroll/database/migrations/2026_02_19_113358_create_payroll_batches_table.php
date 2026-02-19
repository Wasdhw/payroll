<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('payroll_batches', function (Blueprint $table) {
        $table->id();
        $table->string('batch_id')->unique(); 
        $table->date('period_start');
        $table->date('period_end');
        $table->decimal('total_gross', 15, 2);
        $table->decimal('total_net', 15, 2);
        $table->string('processed_by');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_batches');
    }
};
