<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('terms');
        Schema::create('terms', function (Blueprint $table) {
            $table->bigIncrements('term_id');
            $table->integer("parent");
            $table->string("name");
            $table->string("slug");
            $table->text("description")->nullable();
            $table->string("term_group");
            $table->string("post_type");
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
        Schema::dropIfExists('terms');
    }
}
