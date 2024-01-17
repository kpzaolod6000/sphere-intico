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
        if (!Schema::hasTable('calls'))
        {
            Schema::create('calls', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('name')->nullable();
                $table->integer('status');
                $table->integer('direction');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('parent')->nullable();
                $table->integer('parent_id');
                $table->string('description');
                $table->integer('attendees_user')->default(0);
                $table->integer('attendees_contact');
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
        Schema::dropIfExists('calls');
    }
};
