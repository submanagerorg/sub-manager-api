<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingPlanFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_plan_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_plan_id');
            $table->unsignedBigInteger('feature_id');
            $table->timestamps();

            $table->foreign('pricing_plan_id')->references('id')->on('pricing_plans');
            $table->foreign('feature_id')->references('id')->on('features');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_plans');
    }
}
