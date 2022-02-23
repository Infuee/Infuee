<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` ADD `phone` VARCHAR(15) NOT NULL AFTER `name`, ADD `username` VARCHAR(100) NOT NULL AFTER `phone`, ADD `followers` INT(11) NOT NULL AFTER `username`;");
        DB::statement("ALTER TABLE `users` ADD `status` INT(3) NOT NULL COMMENT '1 => \'Pending\', 2=> Active, 3 => Deactivated, 4 => Ban' AFTER `remember_token`;");
        DB::statement("ALTER TABLE `users` ADD `type` INT(2) NOT NULL AFTER `status`;");
        DB::statement("ALTER TABLE `users` ADD `image` VARCHAR(200) NOT NULL AFTER `password`;");
        DB::statement("ALTER TABLE `users` CHANGE `image` `image` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
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
