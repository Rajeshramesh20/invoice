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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->string('invoice_no', 55)->nullable();
            $table->date('invoice_date');
            $table->date('invoice_due_date')->nullable();
            $table->integer('payment_terms')->nullable();
            $table->decimal('total_amount', 11, 2)->nullable();
            $table->decimal('paid_amount', 11, 2)->nullable();
            $table->decimal('balance_amount', 11, 2)->nullable();
            $table->text('additional_text')->nullable();
            $table->unsignedBigInteger('status_id')->nullable()->default(1);
            $table->unsignedBigInteger('customer_id');
            $table->boolean('is_payment_received')->nullable();
            $table->string('location', 255)->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('org_id')->nullable();
            $table->unsignedBigInteger('company_financial_year_id')->nullable();
            $table->unsignedBigInteger('company_bank_details_id')->nullable();
            $table->boolean('status')->default(1);
            $table->enum('email_send_status', ['send', 'not_yet_send', 'failed', 'not_applicable'])->default('not_yet_send');
            $table->enum('created_type', ['internal', 'external'])->nullable();
            $table->enum('created_from', ['system', 'api', 'mdt', 'migration'])->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);

            //  foreign keys
            $table->foreign('status_id')->references('id')->on('invoice_statuses')->onDelete('set null');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
