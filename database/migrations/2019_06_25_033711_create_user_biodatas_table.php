<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBiodatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_biodata', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname');
            $table->string('avatar')->nullable();
            $table->boolean('online')->default(true);
            $table->char('level', 10);
            $table->integer('user_id');
            $table->timestamps();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_biodata', function (Blueprint $table){
            $table->dropForeign(['user_id']);
        });
    }
}
