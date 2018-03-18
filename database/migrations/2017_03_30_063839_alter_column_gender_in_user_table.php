<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnGenderInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('arrive_date');
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->date('arrive_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tripss', function (Blueprint $table) {
            $table->dropColumn('arrive_date');
        });
        Schema::table('trips', function (Blueprint $table) {
            $table->dateTime('arrive_date');
        });
    }
}
