<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('updated_at_payment_id')->nullable();
            $table->double('outstanding_balance')->nullable();
        });

        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->dropColumn('outstanding_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balances');

        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->double('outstanding_balance')->default(0.00);
        });
    }
}
