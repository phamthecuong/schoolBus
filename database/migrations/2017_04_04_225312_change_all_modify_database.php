<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAllModifyDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('class_id');
            $table->dropColumn('school_id');
            $table->dropColumn('departure_id');
            $table->integer('role_id')->nullable();
            $table->integer('profile_id')->nullable();
        });

        Schema::create('parents', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("departure_id")->nullable();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("class_id")->nullable();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("school_id")->nullable();
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->integer('teacher_id')->nullable();
            $table->integer('school_id')->nullable();
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
            $table->integer('class_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('departure_id')->nullable();
            $table->dropColumn('role_id');
            $table->dropColumn('profile_id');
        });

        Schema::dropIfExists('parents');

        Schema::dropIfExists('students');

        Schema::dropIfExists('teachers');

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('teacher_id');
            $table->dropColumn('school_id');
        });
    }
}
