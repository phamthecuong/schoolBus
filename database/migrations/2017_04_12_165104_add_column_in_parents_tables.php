<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInParentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('departure_id');
            $table->dropColumn('school_id');
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('contact_email')->nullable();
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
        Schema::table('parents', function (Blueprint $table) {
            $table->integer('departure_id');
            $table->integer('school_id');
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('full_name');
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
            $table->dropColumn('contact_email');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
