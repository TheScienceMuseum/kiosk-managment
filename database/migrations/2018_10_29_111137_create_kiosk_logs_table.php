<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKioskLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiosk_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kiosk_id');
            $table->string('level');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('kiosk_logs', function (Blueprint $table) {
            $table->foreign('kiosk_id')->references('id')->on('kiosks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kiosk_logs');
    }
}
