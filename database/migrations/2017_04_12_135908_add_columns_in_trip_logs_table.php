<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInTripLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_logs', function (Blueprint $table) {
            $table->integer('created_by')->nullable();
            $table->dateTime('created_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_logs', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('created_at');
        });
    }
}
