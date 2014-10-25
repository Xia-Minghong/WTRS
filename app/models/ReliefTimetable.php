<?php

class ReliefTimetable extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'relief_timetable';

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
    protected $primaryKey = 'record_id';

    protected $fillable = array('mc_id', 'slot', 'relief_short_name');

    public function absence()
    {
        //relaitonship with the Teacher model
        return $this->belongsTo('Absence', 'mc_id', 'mc_id'); //related model, foreign key, local key
    }
}