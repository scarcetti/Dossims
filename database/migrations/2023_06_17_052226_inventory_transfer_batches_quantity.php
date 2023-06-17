<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventoryTransferBatchesQuantity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_transfer_batches', function (Blueprint $table) {
            $table->integer('pcs')->nullable();
            $table->decimal('meters')->nullable();
            $table->dropColumn('arrival_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_transfer_batches', function (Blueprint $table) {
            $table->dropColumn('pcs');
            $table->dropColumn('meters');
        });
    }
}
