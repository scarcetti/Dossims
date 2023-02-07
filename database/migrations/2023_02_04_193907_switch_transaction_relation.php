<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SwitchTransactionRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_payment_id')->nullable();
            $table->foreign('transaction_payment_id')->references('id')->on('transaction_payments');
        });

        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_payment_id']);
            $table->dropColumn('transaction_payment_id');
        });
    }
}
