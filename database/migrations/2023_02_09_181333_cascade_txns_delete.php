<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeTxnsDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->foreign('transaction_id')
            ->references('id')->on('transactions')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['transaction_item_id']);
            $table->foreign('transaction_item_id')
            ->references('id')->on('transaction_items')
            ->onUpdate('cascade')
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
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->foreign('transaction_id')
            ->references('id')->on('transactions')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['transaction_item_id']);
            $table->foreign('transaction_item_id')
            ->references('id')->on('transaction_items')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });
    }
}
