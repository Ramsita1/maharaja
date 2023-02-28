<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresSurgeChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_surge_charges', function (Blueprint $table) {
            $table->bigIncrements('store_surge_id');
            $table->integer('store_id');
            $table->date('date');
            $table->string('reason');
            $table->integer('percentage');
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
        Schema::dropIfExists('stores_surge_charges');
    }
}
