<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_room', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('chat_room_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('chat_room_id');
            $table->integer('job_id')->nullable();
            $table->timestamps();
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->integer('chat_room_id')->nullable()->after('participant_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_room');
    }
}
