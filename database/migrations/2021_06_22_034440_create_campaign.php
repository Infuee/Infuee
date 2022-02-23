<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('campaigns');
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->longText('description');
            $table->string('location', 500);
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('website', 500)->nullable();
            $table->string('image')->nullable();
            $table->string('slug', 500);
            $table->string('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['title']);
        });
        Schema::dropIfExists('jobs');
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id')->nullable()->unsigned();
            $table->string('title', 500);
            $table->longText('description');
            $table->integer('category_id');
            $table->integer('influencers');
            $table->integer('duration');
            $table->integer('promo_days');
            $table->float('price');
            $table->string('image')->nullable();
            $table->string('slug', 500);
            $table->string('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['title', 'campaign_id', 'price', 'category_id']);
            // $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
        Schema::dropIfExists('job_platforms');
        Schema::create('job_platforms', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id')->unsigned();
            $table->integer('platform_id')->unsigned();
            $table->timestamps();
            $table->index(['job_id', 'platform_id']);
            // $table->foreign('job_id')->references('id')->on('jobs');
            // $table->foreign('platform_id')->references('id')->on('social_platform');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_platforms');
    }
}
