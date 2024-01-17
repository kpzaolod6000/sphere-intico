<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('goal_trackings'))
        {
            Schema::create(
                'goal_trackings', function (Blueprint $table){
                $table->bigIncrements('id');
                $table->integer('branch');
                $table->integer('goal_type');
                $table->string('rating')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('subject')->nullable();
                $table->string('target_achievement')->nullable();
                $table->longText('description')->nullable();
                $table->integer('status')->default(0);
                $table->integer('progress')->default(0);
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
        Schema::dropIfExists('goal_trackings');
    }
}
