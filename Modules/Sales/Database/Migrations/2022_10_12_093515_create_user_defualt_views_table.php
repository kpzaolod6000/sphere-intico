<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDefualtViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_defualt_views'))
        {
            Schema::create('user_defualt_views', function (Blueprint $table) {
                $table->id();
                $table->string('module')->nullable();
                $table->string('route')->nullable();
                $table->string('view')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('user_id')->default(0);
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
        Schema::dropIfExists('user_defualt_views');
    }
}
