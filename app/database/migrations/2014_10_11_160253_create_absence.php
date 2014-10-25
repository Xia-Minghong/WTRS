<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsence extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('absence')) {
            CreateAbsence::down();
        }
        Schema::create('absence', function(Blueprint $table)
        {
            $table->increments('mc_id');    //defualt primary key
            $table->string('short_name');
            $table->date('date');
            $table->integer('type');
        });
        Schema::table('absence', function($table)
        {
            $table->foreign('short_name')->references('short_name')->on('teacher')->onDelete('cascade');
            //enforce referential integrity on the foreign key
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('absence');
	}

}
