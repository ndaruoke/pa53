<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateSequenceTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sequences', function ($table) {
            $table->string('transaction_type');
            $table->integer('position_id')->nullable()->unsigned();
        });
    }

}
