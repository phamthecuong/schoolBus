<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyTripDeparturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_departures', function (Blueprint $table) {
            $table->integer('trip_id')->unsigned()->change();
            $table->integer('departure_id')->unsigned()->change();
            $table->foreign('trip_id')
                  ->references('id')->on('trips')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('departure_id')
                  ->references('id')->on('departures')
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
        Schema::table('trip_departures', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropForeign(['departure_id']);
        });
    }
}
