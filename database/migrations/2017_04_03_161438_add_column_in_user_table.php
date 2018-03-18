<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->char('avatar_id', 32)->nullable();
        });
        Schema::table('trip_departures', function (Blueprint $table) {
            $table->dateTime('finish_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_id');
        });

        Schema::table('trip_departures', function (Blueprint $table) {
            $table->dropColumn('finish_at');
        });
    }
}
