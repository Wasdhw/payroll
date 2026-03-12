<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('employees', function (Blueprint $table) {
        $table->dropColumn(['emergency_contact_name', 'emergency_contact_phone']);
    });
}

public function down()
{
    Schema::table('employees', function (Blueprint $table) {
        $table->string('emergency_contact_name')->nullable();
        $table->string('emergency_contact_phone')->nullable();
    });
}
};
