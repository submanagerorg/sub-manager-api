<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique(); 
            $table->unsignedBigInteger('service_id');
            $table->string('status')->default('pending'); 
            $table->json('request_data');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_transaction_id');
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wallet_transaction_id')->references('id')->on('wallet_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_payment_requests');
    }
};
