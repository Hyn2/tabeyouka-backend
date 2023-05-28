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
        if (!Schema::hasTable('local_semester_comments')) {
            Schema::create('local_semester_comments', function (Blueprint $table) {
                $table->id()->unique();
                $table->unsignedBigInteger('author_id')->nullable();
                $table->text('comment_text')->nullable(false);
                $table->timestamps();
            });
        }
        Schema::table('local_semester_comments', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('local_semester_comments');
    }
};
