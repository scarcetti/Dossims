<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryInboundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('inventory_inbounds', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('arrival_date')->nullable();
            $table->string('referrer')->nullable();
            $table->string('referrer_contact')->nullable();
            $table->unsignedBigInteger('distributor_id')->nullable();

            $table->foreign('distributor_id')->references('id')->on('distributors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distributors');
        Schema::dropIfExists('inventory_inbounds');
    }
}
