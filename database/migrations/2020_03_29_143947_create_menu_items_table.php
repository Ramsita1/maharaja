<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('menu_item_id');
            $table->integer('store_id');
            $table->string("item_name");
            $table->text("item_description")->nullable();
            $table->string("item_image")->nullable();
            $table->integer("item_price")->default(0);
            $table->integer("item_sale_price")->default(0);
            $table->integer("item_discount")->default(0);
            $table->date("item_discount_start")->nullable();
            $table->date("item_discount_end")->nullable();
            $table->integer("item_category")->default(0);
            $table->integer("item_type")->default(0);
            $table->integer("menu_order")->default(0);
            $table->string('item_is')->default('Simple');
            $table->string("item_display_in")->nullable();
            $table->text("item_for")->nullable();
            $table->string("show_at_home")->nullable();
            $table->string("is_delicous")->nullable();
            $table->string("is_you_may_like")->nullable();
            $table->enum("item_status",['Active','Inactive']);
            $table->integer("is_non_discountAble")->default(0);
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
        Schema::dropIfExists('menu_items');
    }
}
