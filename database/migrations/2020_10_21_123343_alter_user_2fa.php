<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUser2fa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` ADD `is_two_fa` BOOLEAN NOT NULL DEFAULT FALSE AFTER `type`;");
        DB::statement("ALTER TABLE `users` ADD `otp` INT(6) NULL DEFAULT NULL AFTER `is_two_fa`;");
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
