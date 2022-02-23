<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `name` `first_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        DB::statement("ALTER TABLE `users` ADD `last_name` VARCHAR(50) NULL AFTER `first_name`;");
        DB::statement("ALTER TABLE `users` ADD `country` INT(11) NOT NULL AFTER `image`, ADD `country_code` VARCHAR(8) NOT NULL AFTER `country`, ADD `city` VARCHAR(50) NOT NULL AFTER `country_code`, ADD `state` VARCHAR(50) NOT NULL AFTER `city`, ADD `school` VARCHAR(200) NOT NULL AFTER `state`, ADD `date_of_bith` DATE NULL DEFAULT NULL AFTER `school`;");
        DB::statement("ALTER TABLE `users` CHANGE `school` `school` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        DB::statement("ALTER TABLE `users` CHANGE `username` `username` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        DB::statement("ALTER TABLE `users` CHANGE `followers` `followers` INT(11) NULL;");
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
