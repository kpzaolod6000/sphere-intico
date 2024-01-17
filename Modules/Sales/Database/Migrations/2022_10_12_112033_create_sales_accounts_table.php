<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sales_accounts'))
        {
            Schema::create('sales_accounts', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('document_id')->default(0);
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
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
                $table->string('type')->default(0);
                $table->string('industry')->default(0);
                $table->string('description')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('sales_accounts');
    }
}
