<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_attributes', function (Blueprint $table) {
            $table->bigIncrements('menu_attr_id');
            $table->integer('store_id');
            $table->string('attr_name');
            $table->string('attr_status');
            $table->string('attr_selection');
            $table->integer('attr_selection_mutli_value_min')->default(0);
            $table->integer('attr_selection_mutli_value_max')->default(0);
            $table->string('attr_type');
            $table->integer('attr_main_choice')->default(0);
            $table->integer('attr_mandatory')->default(0);
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
        Schema::dropIfExists('menu_attributes');
    }
}
