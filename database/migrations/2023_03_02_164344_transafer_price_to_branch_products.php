<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransaferPriceToBranchProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('branch_products', function (Blueprint $table) {
            $table->double('price')->default(0.00)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_products', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->double('price')->default(0.00)->nullable();
        });
    }
}
