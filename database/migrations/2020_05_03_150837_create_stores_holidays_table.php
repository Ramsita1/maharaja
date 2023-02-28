<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_holidays', function (Blueprint $table) {
            $table->bigIncrements('store_holiday_id');
            $table->integer('store_id');
            $table->date('date');
            $table->integer('full_day_off')->default(0);
            $table->time('close_start_time')->nullable();
            $table->time('close_end_time')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('stores_holidays');
    }
}
