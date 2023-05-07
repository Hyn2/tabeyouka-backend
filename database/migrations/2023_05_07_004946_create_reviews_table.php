<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating')->notnull();
            $table->text('review_text')->notnull();
            $table->string('image_file')->notnull();
            $table->unsignedBigInteger('restaurant_id')->notnull(); // only non-negative integers
            $table->timestamps();

            $table->foreign('restaurant_id')->reference('id')->on('restaurants');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
        });
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
};
