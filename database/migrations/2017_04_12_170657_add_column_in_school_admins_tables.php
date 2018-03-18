<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInSchoolAdminsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_admins', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('school_admins', function (Blueprint $table) {
            $table->string('full_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_admins', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
        Schema::table('school_admins', function (Blueprint $table) {
            $table->string('name')->nullable();
        });
    }
}
