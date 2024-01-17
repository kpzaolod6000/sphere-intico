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
        if(!Schema::hasTable('workflow_module_fields'))
        {
            Schema::create('workflow_module_fields', function (Blueprint $table) {
                $table->id();
                $table->integer('workmodule_id');
                $table->string('field_name');
                $table->string('input_type');
                $table->string('model_name')->nullable();
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
        Schema::dropIfExists('workflow_module_fields');
    }
};
