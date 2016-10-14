<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimesheetDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lokasi');
            $table->string('activity');
            $table->timestamp('date');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('timesheet_id');
            $table->integer('leave_id');
            $table->integer('project_id');
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
        Schema::drop('timesheet_details');
    }
}
