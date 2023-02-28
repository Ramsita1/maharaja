<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->integer('user_id');
            $table->text("post_title");
            $table->longtext("post_content")->nullable();
            $table->text("post_excerpt")->nullable();
            $table->enum("post_status",['publish','draft','trash']);
            $table->enum("comment_status",["open","close"]);
            $table->enum("ping_status",["open","close"])->default('open');
            $table->string("post_name");
            $table->BigInteger('post_parent');
            $table->string("guid")->nullable();
            $table->string("media")->nullable();
            $table->integer("menu_order");
            $table->string("post_type");
            $table->string("post_lng")->default('eng');
            $table->string("post_template")->default('default');
            $table->integer("comment_count");
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
        Schema::dropIfExists('posts');
    }
}
