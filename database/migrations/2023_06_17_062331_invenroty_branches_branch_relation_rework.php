<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvenrotyBranchesBranchRelationRework extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_transfers', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->renameColumn('branch_id', 'receiver_branch_id');
            $table->foreign('receiver_branch_id')
                ->references('id')
                ->on('branches')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->bigInteger('sender_branch_id')->nullable();
            $table->foreign('sender_branch_id')
                ->references('id')
                ->on('branches')
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
        Schema::table('inventory_transfers', function (Blueprint $table) {
            $table->dropColumn('receiver_branch_id');
            $table->dropColumn('sender_branch_id');

            $table->bigInteger('branch_id')->nullable();
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
