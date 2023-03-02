<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('contact_no')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->change();
            $table->unsignedBigInteger('business_customer_id')->nullable();

            $table->foreign('business_customer_id')->references('id')->on('business_customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['business_customer_id']);

            $table->unsignedBigInteger('customer_id')->nullable()->change();
            $table->dropColumn('business_customer_id')->nullable();
        });

        Schema::dropIfExists('business_customers');
    }
}
