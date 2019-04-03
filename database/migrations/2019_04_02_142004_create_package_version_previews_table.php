<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageVersionPreviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_version_previews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('package_version_id');
            $table->string('preview_path')->nullable();
            $table->boolean('build_complete')->default(false);
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
        Schema::dropIfExists('package_version_previews');
    }
}
