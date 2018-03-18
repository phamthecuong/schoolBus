<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToStudentParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_parents', function (Blueprint $table) {
            $table->integer('student_id')->unsigned()->change();
            $table->integer('parent_id')->unsigned()->change();
            $table->foreign('student_id')
                  ->references('id')->on('students')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('parent_id')
                  ->references('id')->on('parents')
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
        Schema::table('student_parents', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['parent_id']);
        });
    }
}
