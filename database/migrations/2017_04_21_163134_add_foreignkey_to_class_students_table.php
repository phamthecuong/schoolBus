<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToClassStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_students', function (Blueprint $table) {
            $table->integer('class_id')->unsigned()->change();
            $table->integer('student_id')->unsigned()->change();
            $table->foreign('class_id')
                  ->references('id')->on('classes')
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
        Schema::table('class_students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['student_id']);
        });
    }
}
