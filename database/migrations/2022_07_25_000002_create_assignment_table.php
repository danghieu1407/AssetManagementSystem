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
        Schema::create('assignment', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->integer('asset_id', false, true);
            $table->integer('assigned_by', false, true)->nullable();
            $table->integer('staff_id', false, true);
            $table->date('assigned_date')->nullable();
            $table->string('note')->nullable();
            $table->integer('state');
            //-1 : decline, 0 : waiting for acceptance, 1: accepted

            // Foreign Key
            $table->foreign('asset_id')->references('id')->on('asset');
            $table->foreign('assigned_by')->references('id')->on('user');
            $table->foreign('staff_id')->references('id')->on('user');

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
        Schema::dropIfExists('assignment');
    }
};
