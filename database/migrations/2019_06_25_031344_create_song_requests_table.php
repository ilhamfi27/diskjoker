<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url'); // -> varchar
            $table->integer('room_id'); // -> int
            $table->integer('user_id')->nullable(); // -> int
            $table->char('user_type', 5); // -> char
            $table->char('ip_address', 16)->nullable(); // -> char
            $table->integer('queue'); // -> int
            $table->char('status',3); // -> char
            $table->timestamps();
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('song_requests', function (Blueprint $table){
            $table->dropForeign(['user_id','room_id']);
        });
    }
}
