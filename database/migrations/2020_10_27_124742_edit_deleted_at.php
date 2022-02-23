<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `faq_cat` ADD `deleted_at` TIMESTAMP NULL");
        DB::statement("ALTER TABLE `faq` ADD `deleted_at` TIMESTAMP NULL");
        DB::statement("ALTER TABLE `contact_us` ADD `deleted_at` TIMESTAMP NULL");
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
