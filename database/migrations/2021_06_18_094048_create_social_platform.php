<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialPlatform extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_platform', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->integer('status')->default(1);
            $table->timestamps();

        });

        Schema::create('social_platform_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('platform_id');
            $table->integer('category_id');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->index(['platform_id', 'category_id']);
            // $table->foreign('platform_id')->references('id')->on('social_platform');
            // $table->foreign('category_id')->references('id')->on('categories');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_platform');
        Schema::dropIfExists('social_platform_categories');
    }
}
