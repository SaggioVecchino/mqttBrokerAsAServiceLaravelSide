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
            $table->foreign('project_id')
            ->references('id')
            ->on('projects')
            ->onDelete('set null');
            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')
            ->references('id')
            ->on('groups')
            ->onDelete('set null');
            $table->unsignedInteger('tpic_id')->nullable();
            $table->foreign('topic_id')
            ->references('id')
            ->on('topics')
            ->onDelete('set null');
            $table->unique(['project_id','group_id','topic_id']);
            $table->unsignedInteger('level');
            $table->enum('type', ['publication', 'subscribtion']);
            $table->boolean('allow');
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
