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
     *  Use mc_id as primary key.
     *
     * @var String
     */
    protected $primaryKey = 'mc_id';

    /**
     * Allow mass assignment on the following variables
     *
     * @var array
     */
    protected $fillable = array('short_name', 'date', 'type');

    /**
     * Modeling the relationship with the Teacher model
     *
     * @return mixed
     */
    public function teacher()
    {
        return $this->belongsTo('Teacher', 'short_name', 'short_name');
        //related model, foreign key, local key
    }
}