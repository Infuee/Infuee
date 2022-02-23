<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaptionImageVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_jobs', function (Blueprint $table) {
            $table->integer('radius')->after('lng');
            $table->longText('caption')->after('radius');
            $table->string('image_video')->after('caption');
        });
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
