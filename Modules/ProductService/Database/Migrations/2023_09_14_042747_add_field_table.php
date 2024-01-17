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

        Schema::table('product_services', function (Blueprint $table) {
            // if not exist, add the new column
            if (!Schema::hasColumn('product_services', 'sale_chartaccount_id')) {
                $table->integer('sale_chartaccount_id')->after('unit_id')->default('0');
            }
            if (!Schema::hasColumn('product_services', 'expense_chartaccount_id')) {
                $table->integer('expense_chartaccount_id')->after('sale_chartaccount_id')->default('0');
            }

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_services', function (Blueprint $table) {
        });

    }
};
