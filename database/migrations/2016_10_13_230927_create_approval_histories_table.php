<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApprovalHistoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('date');
            $table->string('note');
            $table->integer('sequence_id');
            $table->integer('timesheet_id');
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
        Schema::drop('approval_histories');
    }
}
