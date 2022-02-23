<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallet', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->double('amount');
            $table->timestamps();
        });
        Schema::create('user_wallet_transaction', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet_id');
            $table->double('amount');
            $table->integer('transaction_type');
            $table->text('description');
            $table->string('transaction_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_wallet');
        Schema::dropIfExists('user_wallet_transaction');
    }
}
