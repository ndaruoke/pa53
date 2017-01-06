<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDecimalTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheet_transport', function ($table) {
            $table->decimal('value', 15, 0)->change();
        });

        Schema::table('timesheet_insentif', function ($table) {
            $table->decimal('value', 15, 0)->change();
        });
    }

}
