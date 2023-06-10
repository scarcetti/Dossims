<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('remarks')->nullable();
            $table->bigInteger('interacting_employee_id')->nullable();

            $table->foreign('interacting_employee_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('general_logs');
    }
}
