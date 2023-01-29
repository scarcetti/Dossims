<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBranchInventoryToBranchProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('branch_inventories', 'branch_products');

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->renameColumn('product_id', 'branch_product_id');
            $table->foreign('branch_product_id')->references('id')->on('branch_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('branch_products', 'branch_inventories');

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['branch_product_id']);
            $table->renameColumn('branch_product_id', 'product_id');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }
}
