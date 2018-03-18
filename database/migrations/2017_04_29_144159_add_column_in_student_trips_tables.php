<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInStudentTripsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_trips', function (Blueprint $table) {
            $table->integer('pick_up_id');
            $table->integer('drop_off_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_trips', function (Blueprint $table) {
            $table->dropColumn('pick_up_id');
            $table->dropColumn('drop_off_id');
        });
    }
}
