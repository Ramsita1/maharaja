<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentmetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_meta', function (Blueprint $table) {
            $table->bigIncrements('comment_meta_id');
            $table->unsignedBigInteger('comment_id');
            $table->foreign('comment_id')->references('comment_id')->on('comments');
            $table->string("meta_key");
            $table->longtext("meta_value")->nullable();
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
        Schema::dropIfExists('commentmeta');
    }
}
