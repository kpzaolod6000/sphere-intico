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
        if (!Schema::hasTable('Internalknowledge')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('book');
                $table->string('title');
                $table->string('description');
                $table->string('type')->default('document');
                $table->longText('content')->nullable();
                $table->integer('created_by')->default('0');
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('articles');
    }
};
