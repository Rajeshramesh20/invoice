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
        Schema::create('mail_history', function (Blueprint $table) {
            $table->id('mail_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('email')->nullable();
            $table->string('content')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_history');
    }
};
