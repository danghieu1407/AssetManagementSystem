<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->string('staff_code')->nullable();
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->date('date_of_birth');
            $table->date('joined_date');
            $table->boolean('gender')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('base_username')->nullable();
            $table->boolean('admin')->default(false);
            $table->string('location')->nullable();
            $table->integer('state')->default(0); // check validate 0 = new , 1 = active , -1 = disabled`

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
        Schema::dropIfExists('user');
    }
}
