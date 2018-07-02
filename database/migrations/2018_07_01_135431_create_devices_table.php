<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device_name');
            $table->unsignedInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('device_groups')->onDelete('set null');
            $table->unique(['group_id','project_id','device_name']);
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
        Schema::dropIfExists('devices');
    }
}
