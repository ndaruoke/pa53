<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectDetailInsentif extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('timesheet_insentif', function (Blueprint $table) {
            $table->increments('id');
            $table->string('timesheet_id');
            $table->string('project_id');
            $table->string('date');
            $table->string('value');
            $table->string('keterangan');
            $table->string('status');
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
        //
    }
}