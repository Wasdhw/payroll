<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('employee_id')->constrained()->onDelete('cascade'); 
        
        $table->date('attendance_date');
        $table->string('status')->default('Present'); 
        $table->decimal('overtime_hours', 5, 2)->nullable()->default(0);
        $table->decimal('allowance', 10, 2)->nullable()->default(0);
        $table->decimal('incentive', 10, 2)->nullable()->default(0);
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
