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
        Schema::create('teammate', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('name');
            $table->string('profile_image')->nullable(false);
            $table->string('part');
            $table->text('description')->nullable(false);
            $table->string('github_link')->nullable(false);
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
        Schema::dropIfExists('teammate');
    }
};
