<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCounpons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `counpons` ADD `status` TINYINT NULL DEFAULT '1' AFTER `max_price`");
        DB::statement("ALTER TABLE `counpons` CHANGE `type` `type` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'percentage,flat'");
        DB::statement("RENAME TABLE `infuee`.`counpons` TO `infuee`.`coupons`");
        DB::statement("ALTER TABLE `coupons` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`");
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
