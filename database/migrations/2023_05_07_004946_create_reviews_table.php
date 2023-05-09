<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('author_id')->nullable();
                $table->unsignedInteger('restaurant_id')->nullable();
                $table->integer('rating')->nullable(false);
                $table->text('review_text')->nullable(false);
                $table->string('image_file')->nullable(false);
                $table->timestamps();
            });

            Schema::table('reviews', function (Blueprint $table) {
                $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
