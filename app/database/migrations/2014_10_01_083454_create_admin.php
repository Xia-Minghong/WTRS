<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmin extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //If the table exists, drop it
        if (Schema::hasTable('admin')) {
            CreateAdmin::down();
        }

        Schema::create('admin', function(Blueprint $table)
        {
            $table->increments('id');   //defualt primary key
            $table->string('username', 30);
            $table->string('password', 64);
            $table->rememberToken();    //record cookie token to prevent cookie hijacking
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin');
    }

}
