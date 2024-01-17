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
        if (!Schema::hasTable('sales_invoice_items'))
        {
            Schema::create('sales_invoice_items', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id')->default(0);
                $table->string('item')->nullable();
                $table->integer('quantity')->default(0);
                $table->integer('price')->default(0);
                $table->float('discount')->default(0.00);
                $table->string('tax')->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('sales_invoice_items');
    }
};
