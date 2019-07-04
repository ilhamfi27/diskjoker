<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnOnlineOnUserBiodataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_biodata', function (Blueprint $table){
            $table->dropColumn('online');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_biodata', function (Blueprint $table){
            $table->boolean('online')
                  ->after('avatar')
                  ->default(false);
        });
    }
}
