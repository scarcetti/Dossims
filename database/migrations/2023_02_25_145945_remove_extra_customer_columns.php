<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveExtraCustomerColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('birthdate');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('province');
            $table->dropColumn('zipcode');
            $table->dropColumn('email');
            $table->dropColumn('type');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_placement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_placement');
        });
    }
}
