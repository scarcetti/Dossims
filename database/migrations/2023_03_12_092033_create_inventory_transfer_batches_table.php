<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransferBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transfer_batches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('product_id');
            $table->double('price')->nullable();
            $table->unsignedBigInteger('inventory_inbound_id')->nullable();
            $table->unsignedBigInteger('inventory_outbound_id')->nullable();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('inventory_inbound_id')->references('id')->on('inventory_inbounds');
            $table->foreign('inventory_outbound_id')->references('id')->on('inventory_outbounds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transfer_batches');
    }
}
