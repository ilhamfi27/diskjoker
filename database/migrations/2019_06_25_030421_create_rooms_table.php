<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('url');
            $table->integer('user_id');
            $table->boolean('active');
            $table->integer('max_song_per_user')->default(5);
            $table->integer('max_song_per_guest')->default(3);
            $table->integer('user_max_song_limit_time')->default(10800);
            $table->integer('guest_max_song_limit_time')->default(86400);
            $table->integer('max_song_duration')->default(300);
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
        Schema::dropIfExists('rooms', function (Blueprint $table){
            $table->dropForeign(['user_id']);
        });
    }
}
