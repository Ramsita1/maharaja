<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresDeliveryLocationPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_delivery_location_price', function (Blueprint $table) {
            $table->bigIncrements('store_delivery_location_id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->string("suburb");
            $table->string("city");
            $table->string("postal_code");
            $table->decimal('minimum_delivery_charge', 10, 2);
            $table->decimal('minimum_delivery_order', 10, 2);
            $table->decimal("store_delivery_partner_commission")->default(0);
            $table->decimal("store_delivery_partner_compensation")->default(0);
            $table->decimal('charges', 10, 2);
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
        Schema::dropIfExists('stores_delivery_location_price');
    }
}
