<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->integer('answer_id')->unsigned();
            $table->integer('user_id')->unsigned()->comment("who filled");
            $table->dateTime('finished_at')->nullable($value = true);
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('tests');
            $table->foreign('user_id')->references('id') ->on('users');
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->foreign('question_id')->references('id') ->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_answers');
    }
}
