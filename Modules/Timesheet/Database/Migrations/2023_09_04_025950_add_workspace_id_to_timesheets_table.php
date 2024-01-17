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
        if (Schema::hasTable('timesheets') && !Schema::hasColumn('timesheets', 'workspace_id'))
        {
            Schema::table('timesheets', function (Blueprint $table)
            {
                $table->integer('workspace_id')->nullable()->after('created_by');
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
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn('workspace_id');
        });
    }
};
