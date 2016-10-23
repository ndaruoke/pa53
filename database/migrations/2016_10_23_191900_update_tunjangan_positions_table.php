<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTunjanganPositionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('tunjangan_roles', 'tunjangan_positions');

        Schema::table('tunjangan_positions', function ($table) {
            $table->renameColumn('role_id', 'position_id');
        });
    }

}
