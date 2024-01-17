<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sales_invoice_payments'))
        {
            Schema::create('sales_invoice_payments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('transaction_id')->default(0);
                $table->unsignedBigInteger('client_id')->default(0);
                $table->unsignedBigInteger('invoice_id')->default(0);
                $table->float('amount')->default(0.00);
                $table->date('date');
                $table->string('receipt')->nullable();
                $table->string('payment_type',100)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_payments');
    }
};
