<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('opportunities'))
        {
            Schema::create('opportunities', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('campaign')->default(0);
                $table->string('name')->nullable();
                $table->integer('account')->default(0);
                $table->integer('stage')->default(0);
                $table->float('amount')->default(0.00);
                $table->string('probability')->nullable();
                $table->string('close_date')->nullable();
                $table->integer('contact')->default(0);
                $table->string('lead_source')->nullable();
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
        Schema::dropIfExists('opportunities');
    }
}
