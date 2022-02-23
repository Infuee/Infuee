<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialUserNameToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_username')->after('username')->nullable();
            $table->string('youtube_username')->after('facebook_username')->nullable();
            $table->string('twitter_username')->after('youtube_username')->nullable();
            $table->string('tiktok_username')->after('twitter_username')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('facebook_username')->after('username')->nullable();
            $table->dropColumn('youtube_username')->after('facebook_username')->nullable();
            $table->dropColumn('twitter_username')->after('youtube_username')->nullable();
            $table->dropColumn('tiktok_username')->after('twitter_username')->nullable();
        });
    }
}
