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
        if (!Schema::hasTable('common_cases'))
        {
            Schema::create('common_cases', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('name')->nullable();
                $table->string('number')->nullable();
                $table->integer('status')->default(0);
                $table->integer('account')->default(0);
                $table->integer('priority')->default(0);
                $table->integer('contact')->default(0);
                $table->integer('type')->default(0);
                $table->longtext('description')->nullable();
                $table->string('attachments')->nullable();
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
        Schema::dropIfExists('common_cases');
    }
};
