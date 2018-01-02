<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable($value = true);
            $table->date('start_date')->nullable($value = true);
            $table->date('finish_date')->nullable($value = true); 
            $table->integer('user_id')->unsigned()->comment("who create");  
            $table->integer('time_limit')->unsigned()->comment("in seconds");      
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
