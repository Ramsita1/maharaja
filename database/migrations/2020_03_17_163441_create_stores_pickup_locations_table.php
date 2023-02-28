<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresPickupLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_pickup_locations', function (Blueprint $table) {
            $table->bigIncrements('store_pickup_location_id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->string("suburb");
            $table->string("city");
            $table->string("postal_code");
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
        Schema::dropIfExists('store_pickup_locations');
    }
}
