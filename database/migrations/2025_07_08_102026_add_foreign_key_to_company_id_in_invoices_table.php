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
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->change();

            // Add the foreign key
            $table->foreign('company_id')
                ->references('company_id') // Make sure this column exists in the companies table
                ->on('companies')
                ->onDelete('set null'); // Set to null if the company is deleted  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
