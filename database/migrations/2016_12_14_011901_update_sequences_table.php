<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateSequencesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sequences', function ($table) {
            $table->dropColumn('transaction_type');
        });
        Schema::table('sequences', function ($table) {
            $table->integer('transaction_type');
        });
    }

}
