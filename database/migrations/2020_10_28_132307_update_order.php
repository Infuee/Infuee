<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cart` ADD `status` TINYINT NULL DEFAULT '1'");
        DB::statement("ALTER TABLE `cart_items` ADD `status` TINYINT NULL DEFAULT '1'");
        DB::statement("ALTER TABLE `orders` ADD `status` TINYINT NULL DEFAULT '1'");
        DB::statement("ALTER TABLE `order_items` ADD `status` TINYINT NULL DEFAULT '1'");
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
