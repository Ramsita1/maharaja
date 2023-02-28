<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresOnlineOrderTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_online_order_timings', function (Blueprint $table) {
            $table->bigIncrements('store_online_order_timing_id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->text("weekdays")->nullable();
            $table->string("comment")->nullable();
            $table->date("from_date")->nullable();
            $table->date("to_date")->nullable();
            $table->time("from_time")->nullable();
            $table->time("to_time")->nullable();
            $table->string("type");
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
        Schema::dropIfExists('stores_online_order_timings');
    }
}
