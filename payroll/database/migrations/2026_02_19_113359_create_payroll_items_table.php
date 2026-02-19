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
    Schema::create('payroll_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('payroll_batch_id')->constrained()->onDelete('cascade');
        $table->foreignId('employee_id')->constrained();
        $table->decimal('basic_pay', 10, 2);
        $table->decimal('additions', 10, 2);
        $table->decimal('deductions', 10, 2);
        $table->decimal('net_pay', 10, 2);
        $table->json('details'); // Stores the full breakdown (SSS, PHIC, etc.)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
