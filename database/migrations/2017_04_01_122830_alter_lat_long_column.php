<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLatLongColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departures', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('departures', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departures', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
        Schema::table('departures', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
        });
    }
}
