<?php

class MCController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /mc
	 *
	 * @return Response
	 */
    public function MClist()
    {
        $MClist = $this->getMClist();

        return View::make('admin/reports/showMClist')->with('results',$MClist);
    }

    public function MCscore()
    {
        $MCscore = $this->getMCscores();

        return View::make('admin/reports/showMCscore')->with('results',$MCscore);
    }

    private function getMClist()
    {
        return DB::table('absence')->join('teacher', 'absence.short_name', '=', 'teacher.short_name')
            ->get();
//        return Teacher::whereIn('short_name',Absence::where('date','=',new DateTime('today'))->lists('short_name'))->has('absence')->get();
    }

    private function getMCscores()
    {
        return Teacher::join('mc_score', 'teacher.short_name', '=', 'mc_score.short_name')
            ->get();
    }
    

	/**
	 * Show the form for creating a new resource.
	 * GET /mc/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin/actions/createMC');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /mc
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

                        //reduce 1 mc score from the teacher
                        MCScore::find($teacher['short_name'])->decrement('mc_score');
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

                    //reduce 1 mc score from the teacher who applies.
                    MCScore::find($short_name)->decrement('mc_score');
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
	 * Show the form for editing the specified resource.
	 * GET /mc/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
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
	 * Remove the specified resource from storage.
	 * DELETE
	 *
	 * @return Response
	 */
	public function delete()
	{
        $target = Absence::find(Input::get('mc_id'));
        $short_name = $target->short_name;

        $target->delete();

        //adding back the MC score
        MCScore::find($short_name)->increment('mc_score');


        return Redirect::to('admin/reports/MClist')->withErrors(['success' => true]);
	}

}