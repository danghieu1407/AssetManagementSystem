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
        Schema::create('asset', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->string('asset_code')->nullable();
            $table->string('name');
            $table->string('category_id', 2)->nullable();
            $table->string('specification')->nullable();
            $table->date('installed_date');                                                             
            $table->integer('state')->default(1);
            //0 : not available, 1 : available, 2 : assigned
            //-1 : waiting for recycling, -2 : recycled                         
            $table->string('location')->nullable();

            // Foreign Key
            $table->foreign('category_id')->references('id')->on('category');

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
        Schema::dropIfExists('asset');
    }
};
