<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelateBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balances', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('updated_at_payment_id')->references('id')->on('transaction_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balances', function (Blueprint $table) {
            //
        });
    }
}
