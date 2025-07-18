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
        Schema::create('employee_salary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('base_salary', 12, 2);
            $table->string('pay_grade')->nullable();
            $table->enum('pay_frequency', ['Monthly', 'Weekly', 'Bi-Weekly']);
            $table->string('bank_account_details')->nullable();
            $table->string('tax_identification_number')->nullable();
            $table->decimal('bonuses', 10, 2)->nullable();
            $table->decimal('deductions', 10, 2)->nullable();
            $table->string('provident_fund_details')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary');
    }
};
