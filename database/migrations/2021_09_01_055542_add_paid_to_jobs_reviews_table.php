<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToJobsReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_reviews', function (Blueprint $table) {
             DB::statement("ALTER TABLE jobs_reviews 
             MODIFY rated_by INT(11) COMMENT 'rated_to';");

         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_reviews', function (Blueprint $table) {
            //
        });
    }
}
