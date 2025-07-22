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
        Schema::create('payroll_history', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_id');
            $table->date('pay_date');
            $table->enum('pay_frequency', ['Monthly', 'Weekly', 'Bi-Weekly'])->default('Monthly');
            $table->enum('status', ['In Progress', 'Calculation', 'Completed'])->default('In Progress');
            $table->integer('total_count');
            $table->integer('success');
            $table->integer('failed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_history');
    }
};
