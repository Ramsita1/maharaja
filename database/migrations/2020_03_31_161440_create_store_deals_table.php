<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_deals', function (Blueprint $table) {
            $table->bigIncrements('deal_id');
            $table->integer('store_id');
            $table->string('deal_title');
            $table->text('deal_description');
            $table->string('deal_type');
            $table->decimal('discount');
            $table->decimal('min_order');
            $table->decimal('max_discount');            
            $table->integer('menu_item_id');
            $table->integer('category_id');
            $table->date('start_date');
            $table->date('end_date');   
            $table->time('start_time');   
            $table->time('end_time');   
            $table->text('week_of_day')->nullable();
            $table->text('location')->nullable();    
            $table->integer('buy_item')->nullable();
            $table->integer('buy_item_qnty')->nullable();   
            $table->integer('get_item')->nullable();
            $table->integer('get_item_qnty')->nullable();   
            $table->integer('is_deal_auto_apply')->default(0);   
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
        Schema::dropIfExists('store_deals');
    }
}
