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
        Schema::create('companies', function (Blueprint $table) {
    
            $table->id('company_id');

            $table->string('company_name');
            $table->string('logo_path')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website_url')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('gstin', 15)->nullable(); 
            $table->boolean('status')->default(true); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps(); 
            $table->boolean('is_deleted')->default(false);

            $table->foreign('address_id')->references('address_id')->on('addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
