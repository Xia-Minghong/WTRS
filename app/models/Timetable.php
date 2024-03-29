<?php

class Timetable extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'timetable';

    /**
     *  Do not use the Eloquent timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;


    /**
     *  Use short_name as primary key.
     *
     * @var String
     */
    protected $primaryKey = array('short_name', 'day');

    /**
     * Modeling the relationship with the Teacher model
     *
     * @return mixed
     */
    public function teacher()
    {
        return $this->belongsTo('Teacher', 'short_name', 'short_name'); //related model, foreign key, local key
    }
}