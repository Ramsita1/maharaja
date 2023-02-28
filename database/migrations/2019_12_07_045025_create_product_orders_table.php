<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->integer('user_id')->default(0);
            $table->integer('store_id')->default(0);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('accpet_term_condition')->nullable();
            $table->text('attributes');
            $table->text('product_detail');
            $table->enum('order_status', ['pending', 'processing','accepted','complete'])->default('pending');
            $table->enum('payment_status', ['pending', 'complete', 'Canceled', 'Decline'])->default('pending');
            $table->string('payment_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_getway')->nullable();
            $table->text('getway_raw')->nullable();            
            $table->string('coupon')->nullable();
            $table->text('coupon_data')->nullable();
            $table->string('coupon_type')->nullable();
            $table->float('discount')->default(0);
            $table->float('tax')->default(0);
            $table->float('sub_total')->default(0);
            $table->float('sur_charge')->default(0);
            $table->float('sub_total_with_surcharge')->default(0);
            $table->float('delivery_price')->default(0);
            $table->float('tip_price')->default(0);
            $table->float('extra_charges')->default(0);
            $table->float('total')->default(0);
            $table->float('grand_total')->default(0);
            $table->integer('assinged_to_driver')->default(0);
            $table->integer('driver_id')->default(0);
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
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
        Schema::dropIfExists('product_orders');
    }
}
