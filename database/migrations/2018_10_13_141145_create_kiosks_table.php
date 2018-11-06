<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKiosksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiosks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->nullable();
            $table->string('location')->unique()->nullable();
            $table->string('asset_tag')->unique()->nullable();
            $table->string('identifier')->unique();
            $table->string('client_version')->nullable();
            $table->string('current_package')->nullable();
            $table->unsignedInteger('package_version_id')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('kiosks', function (Blueprint $table) {
            $table->foreign('package_version_id')->references('id')->on('package_versions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kiosks');
    }
}
