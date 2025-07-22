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
        Schema::table('employee_salary', function (Blueprint $table) {
            $table->decimal('advance', 10, 2)->after('deductions')->nullable();
            $table->unsignedBigInteger('employee_job_details_id')->after('employee_id')->nullable();

            // Add the foreign key
            $table->foreign('employee_job_details_id')
                ->references('id') // Make sure this column exists in the companies table
                ->on('employees_job_details')
                ->onDelete('set null'); // Set to null if the company is deleted  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary', function (Blueprint $table) {
            //
        });
    }
};
