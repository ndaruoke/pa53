<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateApprovalHistoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_histories', function ($table) {
            $table->integer('group_approval_id');
            $table->dateTime('moderated_at')->nullable();
        });
    }

}
