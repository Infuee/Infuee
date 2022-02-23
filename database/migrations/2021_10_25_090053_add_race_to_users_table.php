<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRaceToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('race_id')->after('category');
        });
        Schema::table('my_jobs', function (Blueprint $table) {
            $table->integer('race_id')->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('race_id');
        });
        Schema::table('my_jobs', function (Blueprint $table) {
           $table->dropColumn('race_id');
        });
    }
}
