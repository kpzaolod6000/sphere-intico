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
        if (!Schema::hasTable('sales_invoices'))
        {
            Schema::create('sales_invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->default(0);
                $table->integer('quote_id')->default(0);
                $table->integer('invoice_id')->default(0);
                $table->string('name')->nullable();
                $table->integer('salesorder')->default(0);
                $table->integer('quote')->default(0);
                $table->integer('opportunity')->default(0);
                $table->integer('status')->default(0);
                $table->integer('account')->default(0);
                $table->float('amount')->default(0.00);
                $table->date('date_quoted');
                $table->integer('quote_number')->default(0);
                $table->text('billing_address')->nullable();
                $table->string('billing_city')->nullable();
                $table->string('billing_state')->nullable();
                $table->string('billing_country')->nullable();
                $table->integer('billing_postalcode')->default(0);
                $table->text('shipping_address')->nullable();
                $table->string('shipping_city')->nullable();
                $table->string('shipping_state')->nullable();
                $table->string('shipping_country')->nullable();
                $table->integer('shipping_postalcode')->default(0);
                $table->integer('billing_contact')->nullable();
                $table->integer('shipping_contact')->nullable();
                $table->string('tax')->nullable();
                $table->string('shipping_provider')->nullable();
                $table->string('description')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('sales_invoices');
    }
};
