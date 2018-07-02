<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->string( 'group_name');
            $table->unique(['group_name','project_id']);
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
        Schema::dropIfExists('device_groups');
    }
}
