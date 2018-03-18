<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLBMUnreadMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_b_m_unread_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->char('conversation_id', 32)->nullable();
            $table->char('item_id', 32)->nullable();
            $table->char('user_id', 32)->nullable();
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
        Schema::dropIfExists('l_b_m_unread_messages');
    }
}
