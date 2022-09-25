<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returning', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->integer('assignment_id', false, true);
            $table->integer('accepted_by', false, true)->nullable(); //accepted by
            $table->integer('requested_by', false, true); //request by
            $table->date('returned_date')->nullable();
            $table->integer('state')->default('0');
            //0 : waiting for returning, 1 : completed

            // Foreign Key
            $table->foreign('accepted_by')->references('id')->on('user');
            $table->foreign('requested_by')->references('id')->on('user');
            $table->foreign('assignment_id')->references('id')->on('assignment');

            //Time stamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returning');
    }
};
