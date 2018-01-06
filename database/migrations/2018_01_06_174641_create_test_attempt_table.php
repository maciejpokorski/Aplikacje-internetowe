<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestAttemptTable extends Migration
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
            $table->longText('question_and_answers')->nullable($value = true);
            $table->integer('score')->nullable($value = false)->default(0);
            $table->integer('user_id')->unsigned()->comment("who filled");
            $table->dateTime('finished_at')->nullable($value = true);
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
