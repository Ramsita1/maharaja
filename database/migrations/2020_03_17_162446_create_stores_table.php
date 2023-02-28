<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('store_id');
            $table->integer('user_id');
            $table->string("store_title");
            $table->longtext("store_content")->nullable();
            $table->enum("store_status",['open','close']);
            $table->string("store_extra_charges")->default('no');
            $table->string("store_enable_tax")->default('no');
            $table->string("store_enable_sur_charge")->default('no');
            $table->string("store_enable_tip")->default('no');
            $table->decimal("store_tax")->default(0);
            $table->decimal("store_sur_charges")->default(0);
            $table->string("store_delivery_boy_tips")->nullable();
            $table->string("store_name");
            $table->string("store_address")->nullable();
            $table->string("store_postalCode")->nullable();
            $table->string("store_city")->nullable();
            $table->string("store_suburb")->nullable();
            $table->string("store_country")->nullable();
            $table->string("store_pickup_minOrder")->nullable();
            $table->string("store_delivery_minOrder")->nullable();
            $table->string("store_food_type")->nullable();
            $table->string("store_location_phone")->nullable();
            $table->string("store_location_email")->nullable();
            $table->string("store_menu_style")->nullable();
            $table->string("media")->nullable();
            $table->integer("sort_order")->default(0);
            $table->string("post_template")->default('default');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('stores');
    }
}
