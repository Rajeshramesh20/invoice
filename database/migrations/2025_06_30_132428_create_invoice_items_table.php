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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id('invoice_items_id');

            $table->unsignedBigInteger('invoice_id');
            $table->string('item_name', 150)->nullable();
            $table->string('item_unit', 250)->nullable();
            $table->decimal('quantity', 10, 2);
            $table->text('details')->nullable();
            $table->string('location', 255)->nullable();
            $table->decimal('unit_price', 11, 2)->nullable();
            $table->decimal('net_amount', 11, 2)->nullable();
            $table->decimal('gst_percent', 5, 2)->nullable();
            $table->decimal('gst_amount', 11, 2)->nullable();
            $table->decimal('total', 11, 2)->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('org_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);

            // Foreign Key 
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
