<?php
class MCScore extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mc_score';

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

    protected $fillable = array('mc_score');

    public function teacher()
    {
        //relaitonship with the Teacher model
        return $this->hasOne('Teacher', 'short_name', 'short_name'); //related model, foreign key, local key
    }
}

