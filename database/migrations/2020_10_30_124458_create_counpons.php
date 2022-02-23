<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounpons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counpons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('discount')->nullable();
            $table->float('min_price', 8, 2);
            $table->float('max_price', 8, 2);
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
        Schema::dropIfExists('counpons');
    }
}
