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
        Schema::table('wallet_histories', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);

            $table->renameColumn('transaction_id', 'wallet_transaction_id');
            
            $table->foreign('wallet_transaction_id')
                  ->references('id')
                  ->on('wallet_transactions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_histories', function (Blueprint $table) {
            $table->dropForeign(['wallet_transaction_id']);

            $table->renameColumn('wallet_transaction_id', 'transaction_id');

            $table->foreign('transaction_id')
                  ->references('id')
                  ->on('transactions')
                  ->onDelete('cascade');
        });
    }
};
