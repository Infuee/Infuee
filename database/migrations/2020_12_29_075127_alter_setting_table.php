<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `setting` ADD `commission` INT(11) NULL AFTER `stripe_currency`;");
        DB::statement("ALTER TABLE `setting` ADD `smtp_username` VARCHAR(255) NULL AFTER `commission`;");
        DB::statement("ALTER TABLE `setting` ADD `smtp_password` VARCHAR(255) NULL AFTER `smtp_username`;");
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
