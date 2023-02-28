<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items_category', function (Blueprint $table) {
            $table->bigIncrements('item_cat_id');
            $table->integer('store_id');
            $table->string("cat_name");
            $table->string("cat_slug");
            $table->text("cat_description")->nullable();
            $table->string("cat_image")->nullable();
            $table->string("cat_status");
            $table->integer("menu_order")->default(0);
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
        Schema::dropIfExists('menu_items_category');
    }
}
