<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `influencer_requests` DROP `name`, DROP `phone`, DROP `email`;");
        DB::statement("ALTER TABLE `influencer_requests` ADD `user_id` INT(11) NOT NULL AFTER `id`;");
        DB::statement("ALTER TABLE `users` CHANGE `country_code` `country_code` VARCHAR(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        DB::statement("ALTER TABLE `influencer_requests` ADD `f_username` VARCHAR(50) NOT NULL AFTER `followers`, ADD `f_followers` INT(11) NOT NULL AFTER `f_username`;");
        DB::statement("ALTER TABLE `influencer_requests` ADD `category` INT(11) NOT NULL AFTER `f_followers`;");
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
