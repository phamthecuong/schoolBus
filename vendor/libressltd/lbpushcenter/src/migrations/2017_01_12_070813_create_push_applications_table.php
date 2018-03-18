<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_applications', function (Blueprint $table) {
            $table->char('id', 32);
            $table->string('name');
            $table->char('type_id', 32);
            $table->text("server_key")->nullable();
            $table->text("server_secret")->nullable();
            $table->char('pem_file_id', 32)->nullable();
            $table->text("pem_password")->nullable();
            $table->boolean("production_mode")->default(false);
            $table->timestamps();

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
        Schema::dropIfExists('push_applications');
    }
}
