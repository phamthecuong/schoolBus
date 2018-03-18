<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToStudentTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_trips', function (Blueprint $table) {
            $table->integer('student_id')->unsigned()->change();
            $table->integer('trip_id')->unsigned()->change();
            $table->foreign('trip_id')
                  ->references('id')->on('trips')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('student_id')
                  ->references('id')->on('students')
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
        Schema::table('student_trips', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropForeign(['student_id']);
        });
    }
}
