<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelrTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->string('cart_id', 63)->primary();
            $table->integer('order_id')->nullable();
            $table->integer('store_id');
            $table->boolean('test_mode')->default(0);
            $table->decimal('amount');
            $table->string('description');
            $table->string('success_url');
            $table->string('canceled_url');
            $table->string('declined_url');
            $table->string('billing_fname')->nullable();
            $table->string('billing_sname')->nullable();
            $table->string('billing_address_1')->nullable();
            $table->string('billing_address_2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_region')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('lang_code')->nullable();
            $table->string('trx_reference')->nullable();
            $table->boolean('approved')->nullable();
            $table->text('response')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
