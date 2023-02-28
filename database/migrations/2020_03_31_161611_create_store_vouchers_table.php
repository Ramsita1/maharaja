<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_vouchers', function (Blueprint $table) {
            $table->bigIncrements('voucher_id');
            $table->string('code');
            $table->string('description');
            $table->string('discount_type');
            $table->string('store_id');
            $table->decimal('discount')->default(0);
            $table->decimal('max_discount')->default(0);
            $table->decimal('min_order');
            $table->string('usage_for');
            $table->integer('category_id');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('expiry_date');
            $table->time('expiry_time');
            $table->string('usage_many');
            $table->string('usage_many_multiple')->nullable();
            $table->string('week_of_day')->nullable();
            $table->string('location')->nullable();
            $table->string('user_tags')->nullable();
            $table->string('free_delivery')->default(0);
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
        Schema::dropIfExists('store_vouchers');
    }
}
