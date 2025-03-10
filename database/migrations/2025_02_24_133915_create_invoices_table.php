<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('business_type', ['parking', 'retail', 'subscription', 'service']);
            $table->json('item_details');
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'overdue'])->default('pending');
            $table->unsignedBigInteger('issued_by'); // User ID of who issued the invoice
            $table->timestamp('issue_date')->useCurrent();
            $table->timestamp('due_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
