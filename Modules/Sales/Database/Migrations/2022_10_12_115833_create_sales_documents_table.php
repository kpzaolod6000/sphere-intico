<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sales_documents'))
        {
            Schema::create('sales_documents', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('name')->nullable();
                $table->integer('account')->default(0);
                $table->integer('folder')->default(0);
                $table->integer('type')->default(0);
                $table->integer('opportunities')->default(0);
                $table->date('publish_date');
                $table->date('expiration_date');
                $table->integer('status')->default(0);
                $table->string('description')->nullable();
                $table->string('attachment')->nullable();
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
        Schema::dropIfExists('sales_documents');
    }
}
