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
     *  Use record_id as primary key.
     *
     * @var String
     */
    protected $primaryKey = 'record_id';

    /**
     * Allow mass assignment on the following variables
     *
     * @var array
     */
    protected $fillable = array('mc_id', 'slot', 'relief_short_name');

    /**
     * Modeling the relationship with the Teacher model
     *
     * @return mixed
     */
    public function absence()
    {
        return $this->belongsTo('Absence', 'mc_id', 'mc_id'); //related model, foreign key, local key
    }
}