<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInDepartureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departures', function (Blueprint $table) {
            $table->float('lat')->nullable();
            $table->float('long')->nullable();
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->float('lat')->nullable();
            $table->float('long')->nullable();
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
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
}
