<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //If the table exists, drop it
        if (Schema::hasTable('timetable')) {
            CreateTimetable::down();
        }

        Schema::create('timetable', function(Blueprint $table)
        {
            $table->string('short_name');
            $table->integer('day');
            $table->string('slot_1');
            $table->string('slot_2');
            $table->string('slot_3');
            $table->string('slot_4');
            $table->string('slot_5');
            $table->string('slot_6');
            $table->string('slot_7');
            $table->string('slot_8');
            $table->string('slot_9');
            $table->string('slot_10');
            $table->string('slot_11');
            $table->string('slot_12');
            $table->string('slot_13');
        });

        Schema::table('timetable', function ($table)
        {
            //setting composite primary key
            $table->primary(array('short_name', 'day'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
    {
        Schema::drop('timetable');
    }
}
