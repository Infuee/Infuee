<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInfluencer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("ALTER TABLE `influencer_requests` ADD `phone` VARCHAR(15) NOT NULL AFTER `name`, ADD `username` VARCHAR(100) NOT NULL AFTER `phone`, ADD `followers` INT(11) NOT NULL AFTER `username`;");
        DB::statement("ALTER TABLE `influencer_requests` ADD `status` INT(3) NOT NULL AFTER `username`;");
        DB::statement("ALTER TABLE `influencer_requests` ADD `image` VARCHAR(200) NOT NULL AFTER `status`;");
        DB::statement("ALTER TABLE `influencer_requests` CHANGE `image` `image` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        
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
