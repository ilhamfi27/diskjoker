<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnThumbnailDfOnSongRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_requests', function (Blueprint $table){
            $table->string('thumbnail_df')
                  ->default("")
                  ->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_requests', function (Blueprint $table){
            $table->dropColumn('thumbnail_df');
        });
    }
}
