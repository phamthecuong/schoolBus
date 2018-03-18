<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distances', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->integer('about');
            $table->string('name')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distances', function (Blueprint $table) {
            $table->dropColumn('about');
            $table->dropColumn('name');
            $table->integer('amount');

        });
    }
}
