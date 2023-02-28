<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item_attributes', function (Blueprint $table) {
            $table->bigIncrements('item_attr_id');
            $table->integer('menu_item_id');
            $table->integer('user_id');
            $table->integer('menu_attr_id');
            $table->string('attr_name');
            $table->text('attr_desc');
            $table->decimal('attr_price');
            $table->string('attr_status');
            $table->string('attr_size');
            $table->integer('attr_default_choice')->default(0);            
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
        Schema::dropIfExists('item_attributes');
    }
}
