<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_id')->unsigned();
            $table->integer('user_id')->unsigned()->comment("who filled");
            $table->integer('score')->nullable($value = false)->default(0);
            $table->integer('is_finished')->nullable($value = false)->default(0);
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('tests');
            $table->foreign('user_id')->references('id') ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_attempts');
    }
}
