<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInStudentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('class_id');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->string('full_name')->nullable();
            $table->integer('gender')->default(1);
            $table->date('birthday')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('class_id');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('full_name');
            $table->dropColumn('gender');
            $table->dropColumn('birthday');
            $table->dropColumn('address');
            $table->dropColumn('avatar_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
