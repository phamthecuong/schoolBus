<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToDriverTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_trips', function (Blueprint $table) {
            $table->integer('driver_id')->unsigned()->change();
            $table->integer('trip_id')->unsigned()->change();
            $table->foreign('driver_id')
                  ->references('id')->on('drivers')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('trip_id')
                  ->references('id')->on('trips')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_trips', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['trip_id']);
        });
    }
}
