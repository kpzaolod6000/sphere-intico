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
        if (!Schema::hasTable('meetings'))
        {
            Schema::create('meetings', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('name')->nullable();
                $table->integer('status')->default(0);
                $table->date('start_date');
                $table->date('end_date');
                $table->string('parent')->nullable();
                $table->integer('parent_id')->default(0);
                $table->string('description')->nullable();
                $table->integer('attendees_user')->default(0);
                $table->integer('attendees_contact')->default(0);
                $table->integer('attendees_lead')->default(0);
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
        Schema::dropIfExists('meetings');
    }
};
