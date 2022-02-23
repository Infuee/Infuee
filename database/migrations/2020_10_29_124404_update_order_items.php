<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `orders` ADD `coupon_id` INT NULL AFTER `stripe_charge_id`, ADD `discount_price` DOUBLE(8,2) NULL AFTER `coupon_id`");
        DB::statement("ALTER TABLE `order_items` ADD `price` DOUBLE(8,2) NULL AFTER `user_plan_id`");
        DB::statement("ALTER TABLE `order_items` ADD `status` INT NULL AFTER `price`");
        DB::statement("ALTER TABLE `order_items` ADD `transfer_id` VARCHAR(100) NULL AFTER `price`;");
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
