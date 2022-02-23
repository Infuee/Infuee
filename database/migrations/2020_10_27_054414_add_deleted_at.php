<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;");
        DB::statement("ALTER TABLE `influencer_requests` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;");
        DB::statement("ALTER TABLE `plan_categories` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;");
        DB::statement("ALTER TABLE `plans` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;");
        DB::statement("ALTER TABLE `categories` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;");
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
