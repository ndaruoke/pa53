<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateValueInsentif extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheet_insentif', function ($table) {
            $table->dropColumn('value');
        });
        Schema::table('timesheet_insentif', function ($table) {
            $table->decimal('value', 15, 2);
        });

        Schema::table('timesheet_transport', function ($table) {
            $table->dropColumn('value');
        });
        Schema::table('timesheet_transport', function ($table) {
            $table->decimal('value', 15, 2);
        });
    }

}
