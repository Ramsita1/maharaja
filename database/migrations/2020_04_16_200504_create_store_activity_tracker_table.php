<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreActivityTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_activity_tracker', function (Blueprint $table) {
            $table->bigIncrements('activity_tracker_id');
            $table->integer('store_id');
            $table->integer('from_user_id');
            $table->integer('to_user_id');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_activity_tracker');
    }
}
