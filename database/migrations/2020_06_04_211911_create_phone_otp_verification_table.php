<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneOtpVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_otp_verification', function (Blueprint $table) {
            $table->bigIncrements('otp_id');
            $table->string('phone');
            $table->string('otp_code');
            $table->datetime('time');
            $table->string('otp_for');
            $table->integer('otp_status');
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
        Schema::dropIfExists('phone_otp_verification');
    }
}
