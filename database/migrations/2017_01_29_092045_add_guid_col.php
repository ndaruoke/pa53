<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuidCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheet_transport', function ($table) {
            $table->string('guid',50);
        //    $table->string('start_time',5)->change();
        //    $table->string('end_time',5)->change();
            //  $table->integer('year');
        });
        Schema::table('timesheet_insentif', function ($table) {
            $table->string('guid',50);
        //    $table->string('start_time',5)->change();
        //    $table->string('end_time',5)->change();
            //  $table->integer('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
