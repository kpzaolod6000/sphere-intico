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
        if(!Schema::hasTable('conversions'))
        {
            Schema::create('conversions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('ticket_id');
                $table->text('description');
                $table->text('attachments');
                $table->text('sender');
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
        Schema::dropIfExists('conversions');
    }
};
