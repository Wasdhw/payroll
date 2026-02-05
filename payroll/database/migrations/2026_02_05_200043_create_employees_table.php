<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        
        // --- Personal Information ---
        $table->string('employee_id')->unique(); 
        $table->string('first_name');
        $table->string('middle_name')->nullable(); 
        $table->string('last_name');
        $table->date('birth_date'); 
        $table->string('gender'); 
        $table->string('civil_status'); 
        $table->text('address'); 
        $table->string('phone'); 
        $table->string('email')->unique();
        $table->string('emergency_contact_name'); 
        $table->string('emergency_contact_phone'); 
        
        // --- Employment Details ---
        $table->string('department');
        $table->string('job_title');
        $table->string('employment_type'); 
        $table->date('join_date');
        $table->string('work_schedule'); 
        $table->decimal('salary', 10, 2);
        $table->string('status')->default('Active');
        $table->string('supervisor')->nullable(); 

        $table->timestamps();
    });

}
};
