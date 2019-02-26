<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableKioskLogsAddSeenByUserIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiosk_logs', function (Blueprint $table) {
            $table->unsignedInteger('seen_by_user_id')->nullable();
            $table->foreign('seen_by_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kiosk_logs', function (Blueprint $table) {
            $table->dropForeign('kiosk_logs_seen_by_user_id_foreign');
            $table->dropColumn('seen_by_user_id');
        });
    }
}
