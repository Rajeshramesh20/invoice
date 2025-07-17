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
        Schema::create('employees_job_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('job_title');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('reporting_manager')->nullable();
            $table->enum('employee_type', ['full_time', 'part_time', 'contract']);
            $table->enum('employment_status', ['active', 'terminated', 'on_leave']);
            $table->date('joining_date');
            $table->integer('probation_period')->comment('In months');
            $table->date('confirmation_date')->nullable();
            $table->string('work_location');
            $table->string('shift')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_job_details');
    }
};
