<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedAtDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `timesheet_details` MODIFY `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;');
        DB::statement('ALTER TABLE `timesheet_insentif` MODIFY `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;');
        DB::statement('ALTER TABLE `timesheet_transport` MODIFY `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;');

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
