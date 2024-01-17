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
        if(!Schema::hasTable('ticket_fields'))
        {
            Schema::create('ticket_fields', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('type')->default('text');
                $table->string('placeholder')->nullable();
                $table->string('width')->default(6);
                $table->integer('order')->default(0);
                $table->integer('status')->default(1);
                $table->boolean('is_required')->default('1');
                $table->integer('custom_id')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('workspace_id')->default(0);
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
        Schema::dropIfExists('ticket_fields');
    }
};
