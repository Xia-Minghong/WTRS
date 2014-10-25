<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Teacher extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'teacher';

	/**
	 *  Do not use the Eloquent timestamps.
	 *
	 * @var boolean
	 */
    public  $timestamps = false;


    /**
     *  Use short_name as primary key.
     *
     * @var String
     */
    protected $primaryKey = 'short_name';

    public function mc_score()
    {
        //relaitonship with the MCScore model
        return $this->hasOne('MCScore', 'short_name', 'short_name'); //related model, foreign key, local key
    }

    public function absence()
    {
        //relaitonship with the Absence model
        return $this->hasMany('Absence', 'short_name', 'short_name'); //related model, foreign key, local key
    }

    public function timetables()
    {
        //relaitonship with the Timetable model
        return $this->hasMany('Timetable', 'short_name', 'short_name'); //related model, foreign key, local key
    }
}

