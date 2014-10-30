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

    /**
     * Allow mass assignment on the following variables
     * @var array
     */
    protected $fillable = array('mc_score');

    /**
     * Modeling the relationship with the Teacher model
     *
     * @return mixed
     */
    public function teacher()
    {
        return $this->hasOne('Teacher', 'short_name', 'short_name');
        //related model, foreign key, local key
    }
}

