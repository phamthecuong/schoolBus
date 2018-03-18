<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTripLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->increments('id');
        });
    }
}
