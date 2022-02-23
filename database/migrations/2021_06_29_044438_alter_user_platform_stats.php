<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserPlatformStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_platform_stats', function (Blueprint $table) {
            $table->string('username')->nullable()->after('user_id');
        });

        Schema::table('user_wallet_transaction', function (Blueprint $table) {
            $table->integer('created_by')->nullable()->after('transaction_id');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
