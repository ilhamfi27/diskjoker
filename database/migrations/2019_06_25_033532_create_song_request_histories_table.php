<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongRequestHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_request_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url'); // -> varchar
            $table->char('request_by', 5); // -> guest / user
            $table->integer('requester_id')->nullable(); // -> int
            $table->char('requester_ip',16)->nullable(); // -> int
            $table->integer('room_id'); // -> int
            $table->integer('queue'); // -> int
            $table->char('song_status',3); // -> char
            $table->timestamps();
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade');
            $table->foreign('requester_id')
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
        Schema::dropIfExists('song_request_histories', function (Blueprint $table){
            $table->dropForeign(['room_id', 'requester_id']);
        });
    }
}
