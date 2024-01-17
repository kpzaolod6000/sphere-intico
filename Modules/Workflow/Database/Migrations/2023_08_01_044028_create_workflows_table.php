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
        if(!Schema::hasTable('workflows'))
        {
            Schema::create('workflows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('event');
                $table->string('do_this'); 
                $table->string('message');
                $table->longText('do_this_data');
                $table->string('json_data');
                $table->integer('workspace');
                $table->integer('created_by');
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
        Schema::dropIfExists('workflows');
    }
};
