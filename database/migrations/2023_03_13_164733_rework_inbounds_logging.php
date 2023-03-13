<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReworkInboundsLogging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('direction')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('referrer')->nullable();
            $table->string('referrer_contact')->nullable();
            $table->unsignedBigInteger('distributor_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->foreign('distributor_id')->references('id')->on('distributors');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
        Schema::table('inventory_transfer_batches', function (Blueprint $table) {
            $table->dropColumn('inventory_inbound_id');
            $table->dropColumn('inventory_outbound_id');

            $table->unsignedBigInteger('inventory_transfer_id')->nullable();

            $table->foreign('inventory_transfer_id')->references('id')->on('inventory_transfers');
        });

        Schema::dropIfExists('inventory_inbounds');
        Schema::dropIfExists('inventory_outbounds');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
