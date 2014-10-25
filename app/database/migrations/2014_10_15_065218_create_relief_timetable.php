<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReliefTimetable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('relief_timetable')) {
            CreateReliefTimetable::down();
        }

        Schema::create('relief_timetable', function(Blueprint $table)
        {
            $table->increments('record_id');    //defualt primary key
            $table->unsignedInteger('mc_id');
            $table->integer('slot');
            $table->string('relief_short_name');
        });

        Schema::table('relief_timetable', function($table)
        {
            $table->foreign('mc_id')->references('mc_id')->on('absence')->onDelete('cascade');
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
        Schema::drop('relief_timetable');
    }

}

