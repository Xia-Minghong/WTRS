<?php

class MCController extends \BaseController {

    /**
     * Create a view displaying a list of all absences
     *
     * @return mixed
     */
    public function MClist()
    {
        $MClist = $this->getMClist();

        return View::make('admin/reports/showMClist')->with('results',$MClist);
    }

    /**
     * Create a view displaying a list of MC scores
     *
     * @return mixed
     */
    public function MCscore()
    {
        $MCscore = $this->getMCscores();

        return View::make('admin/reports/showMCscore')->with('results',$MCscore);
    }

    /**
     * Obtain a list of absences from database
     *
     * @return mixed
     */
    private function getMClist()
    {
        return DB::table('absence')->join('teacher', 'absence.short_name', '=', 'teacher.short_name')
            ->get();
//        return Teacher::whereIn('short_name',Absence::where('date','=',new DateTime('today'))->lists('short_name'))->has('absence')->get();
    }

    /**
     * Obtain a list of MC scores from database
     *
     * @return mixed
     */
    private function getMCscores()
    {
        return Teacher::join('mc_score', 'teacher.short_name', '=', 'mc_score.short_name')
            ->get();
    }
    

	/**
	 * Show the form for creating a new MC.
     *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin/actions/createMC');
	}

	/**
	 * Store a newly created MC record into database.
     *
	 * @return Response
	 */
	public function store()
	{
        //Authentication checking

        if(Input::get('isAdmin')) {

            //if valid nric
            if (Auth::admin()->check() && $teacher = Teacher::where('nric', '=', Input::get('nric'))->first()) {
                //Create absence record for each day of MC
                $from_date = Input::get('fromdate');
                $to_date = Input::get('todate');

                //for each day on mc
                for($date = $from_date; strtotime($date) <= strtotime($to_date); $date = date('d-m-Y', strtotime($date . "+1 days")) ) {
                    if(idate("w", strtotime($date))!=6 && idate("w", strtotime($date))!=0 &&
                        !Absence::where('short_name','=', $teacher['short_name'])
                        ->where('date','=',date("Y-m-d", strtotime($date)))
                        ->first()
                    ) { //avoid weekends and duplicates

                        //add a new absence record
                        $mc_id = DB::table('absence')->insertGetId(
                            array(
                                'short_name' => $teacher['short_name'],
                                'date' => date("Y-m-d", strtotime($date)),  //storing date in ISO format
                                'type' => Input::get('type'),
                            )
                        );

                        //create blank relief records for each slot having class, to be filled by admin
                        $this->createRelief($mc_id, $teacher['short_name'], $date);

                        //reduce 1 mc score from the teacher if its medical leave
                        if (Input::get('type') == '1') {
                            MCScore::find($teacher['short_name'])->decrement('mc_score');
                        }
                    }
                }

//                return Timetable::where('short_name', '=', $teacher['short_name'])->where('day','=',idate( "w", strtotime($date)))->get();

                return Redirect::to('admin/actions/createMC')->withErrors(['success'=>true]);
            }

            //else if invalid nric
            return Redirect::to('admin/actions/createMC')->with('message', 'Invalid NRIC');

        } elseif(Auth::teacher()->check()) {
            //Create absence record for each day of MC
            $from_date = Input::get('fromdate');
            $to_date = Input::get('todate');
            $short_name = Auth::teacher()->get()->short_name;

            //for each day on mc
            for($date = $from_date; strtotime($date) <= strtotime($to_date); $date = date('d-m-Y', strtotime($date . "+1 days")) ) {
                if(idate("w", strtotime($date))!=6 && idate("w", strtotime($date))!=0 &&
                !Absence::where('short_name','=', $short_name)    //avoid duplicates and weekends
                ->where('date','=',date("Y-m-d", strtotime($date)))
                    ->first()
                ) {
                    //add a new absence record
                    $mc_id = DB::table('absence')->insertGetId(
                        array(
                            'short_name' => $short_name,
                            'date' => date("Y-m-d", strtotime($date)),  //storing date in ISO format
                            'type' => Input::get('type'),
                        )
                    );

                    //create blank relief records for each slot having class, to be filled by admin
                    $this->createRelief($mc_id, $short_name, $date);

                    //reduce 1 mc score from the teacher who applies for a medical leave.
                    if(Input::get('type')=='1') {
                        MCScore::find($short_name)->decrement('mc_score');
                    }
                }
            }

            return Redirect::to('/')->withErrors(['success' => true]);
        } else {
            return Redirect::to('/');
        }

	}


//
//	/**
//	 * Display the specified resource.
//	 * GET /mc/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function show($id)
//	{
//		//
//	}
//
    /**
     * Edit MC scores according to the inputs submitted through forms
     * @return mixed
     */
	public function editScore()
	{
        if(!Input::get('short_name')){  //if reset all
            DB::table('mc_score')->update(array('mc_score' => 0));
        } else {
            //validation
            $validator = Validator::make(Input::all(), array('mc_score' => 'integer', 'short_name' => 'exists:teacher'));

            if (!$validator->fails()) {
                MCScore::find(Input::get('short_name'))->update(array('mc_score' => Input::get('mc_score')));
            }
        }
        return Redirect::to('admin/reports/MCscore');
	}

    /**
     * Create an relief record to be filled for each slot in the timetable of the teacher who is on leave
     * @param $mc_id
     * @param $short_name
     * @param $date
     */
    private function createRelief($mc_id, $short_name, $date)
    {
        $day = idate( "w", strtotime($date));
        $timetable = Timetable::where('short_name', '=', $short_name)->where('day','=',$day)->first();
        if($timetable){
            for ($i = 1; $i <= 13; $i++) {
                if ($timetable['slot_' . $i] != '') {
                    ReliefTimetable::create(array('mc_id'=>$mc_id, 'slot'=>$i));
                }
            }
        }
    }

//
//	/**
//	 * Update the specified resource in storage.
//	 * PUT /mc/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function update($id)
//	{
//		//
//	}
//
    /**
     * Delete an absence record according to the mc_id provided in the form submission
     * @return mixed
     */
	public function delete()
	{
        $target = Absence::find(Input::get('mc_id'));
        $short_name = $target->short_name;

        $target->delete();

        //adding back the MC score if it is a medical leave
        if ($target->type == '1') {
            MCScore::find($short_name)->increment('mc_score');
        }

        return Redirect::to('admin/reports/MClist')->withErrors(['success' => true]);
	}

}