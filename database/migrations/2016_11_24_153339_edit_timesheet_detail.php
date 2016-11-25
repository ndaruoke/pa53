<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTimesheetDetail extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    
    public function up()
    {
        Schema::table('timesheet_details', function ($table) {
           // $table->integer('week');
                $table->string('start_time')->change();
                $table->string('end_time')->change();
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