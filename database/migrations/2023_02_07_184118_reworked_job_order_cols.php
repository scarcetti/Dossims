<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReworkedJobOrderCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn('contract_start');
            $table->dropColumn('contract_end');
            $table->dropColumn('time_in');
            $table->dropColumn('time_out');
            $table->dropColumn('daily_rate');
            $table->dropColumn('currency');

            $table->longText('note')->nullable();
            $table->unsignedBigInteger('transaction_item_id')->nullable();
            $table->foreign('transaction_item_id')->references('id')->on('transaction_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dateTimeTz('contract_start');
            $table->dateTimeTz('contract_end');
            $table->timeTz('time_in');
            $table->timeTz('time_out');
            $table->double('daily_rate');
            $table->string('currency');

            $table->dropColumn('note');
            $table->dropForeign(['transaction_item_id']);
            $table->dropColumn('transaction_item_id');
        });
    }
}
