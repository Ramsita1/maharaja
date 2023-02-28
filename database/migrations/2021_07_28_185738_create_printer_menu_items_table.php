<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrinterMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printer_menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->unsignedBigInteger('printer_id');
            $table->unsignedBigInteger('menu_item_id');
            $table->timestamps();

	    $table->foreign('printer_id')->references('id')->on('printers');
            $table->foreign('menu_item_id')->references('item_cat_id')->on('menu_items_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printer_menu_items');
    }
}
