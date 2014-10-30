<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeacher extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //If the table exists, drop it
        if (Schema::hasTable('teacher')) {
            CreateTeacher::down();
        }

        Schema::create('teacher', function(Blueprint $table)
        {
            $table->string('nric', 9);
            $table->string('title');
            $table->string('full_name');
            $table->string('short_name');
            $table->string('designation');
            $table->integer('grouping');
            $table->string('email_address');
            $table->string('contact_nos');
            $table->rememberToken();    //record cookie token to prevent cookie hijacking
        });

        Schema::table('teacher', function($table) {
            //setting primary key
            $table->primary('short_name');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('teacher');
	}

}
