<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeToMyJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::table('my_jobs', function (Blueprint $table) {
    //         $table->integer('max_age')->change();
    //         $table->integer('min_age')->change();

    //     });

    // }
    public function up(){
    DB::statement('ALTER TABLE my_jobs MODIFY COLUMN max_age integer');
    DB::statement('ALTER TABLE my_jobs MODIFY COLUMN min_age integer');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_jobs', function (Blueprint $table) {
            //
        });
    }
}
