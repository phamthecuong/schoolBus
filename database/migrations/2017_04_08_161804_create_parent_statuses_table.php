<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_statuses', function (Blueprint $table) {
            $table->char('id', 32);
            $table->integer('parent_id');
            $table->integer('trip_id');
            $table->integer('five_hundred')->nullable();
            $table->integer('one_thousand')->nullable();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_statuses');
    }
}
