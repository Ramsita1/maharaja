<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->bigIncrements('link_id');
            $table->string("link_url");
            $table->string("link_name");
            $table->string("link_target");
            $table->string("link_rel");
            $table->integer("post_id")->default(0);
            $table->integer("link_order")->default(0);
            $table->integer("link_parent")->default(0);
            $table->string("link_visible")->default("Y");
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
        Schema::dropIfExists('links');
    }
}
