<?php

class Absence extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'absence';

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
    protected $primaryKey = 'mc_id';

    protected $fillable = array('short_name', 'date', 'type');

    public function teacher()
    {
        //relaitonship with the Teacher model
        return $this->belongsTo('Teacher', 'short_name', 'short_name'); //related model, foreign key, local key
    }
}