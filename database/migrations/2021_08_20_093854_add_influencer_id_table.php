<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfluencerIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wallet_transaction', function (Blueprint $table) {
            $table->integer('influencer_id')->nullable()->after('created_by');
            $table->integer('job_id')->nullable()->after('influencer_id');
            $table->integer('commission')->nullable()->after('job_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wallet_transaction', function (Blueprint $table) {
            //
        });
    }
}
