<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterParentStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parent_statuses', function (Blueprint $table) {
            $table->dropColumn('five_hundred');
            $table->dropColumn('one_thousand');
            $table->integer('departure_id');
            $table->integer('distance_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parent_statuses', function (Blueprint $table) {
            $table->integer('five_hundred')->nullable();
            $table->integer('one_thousand')->nullable();
            $table->dropColumn('distance_id');
            $table->dropColumn('departure_id');
        });
    }
}
