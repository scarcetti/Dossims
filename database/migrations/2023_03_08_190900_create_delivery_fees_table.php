<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_fees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('transaction_payment_id')->nullable();
            $table->double('outside_brgy')->nullable();
            $table->boolean('long')->nullable();
            $table->integer('distance')->nullable();
            $table->double('total')->nullable();

            $table->foreign('transaction_payment_id')->references('id')->on('transaction_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_fees');
    }
}
