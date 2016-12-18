<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimesheetTransportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_transports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timesheet_id');
            $table->integer('project_id');
            $table->timestamp('date');
            $table->string('value');
            $table->string('keterangan');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('timesheet_transports');
    }
}
