<?php

class TimeTableController extends BaseController {

	/**
     * Create a view for:
     * If $short_name specified, show the timetable, if the teacher does not have one, a form is displayed
     * If no $short_name, a list of all teacher without timetables are shown
     *
	 * @return Response
	 */
    public function index($short_name = null)
	{
        if(!$short_name || $short_name && Timetable::where('short_name','=',$short_name)->first()) {
            $timetables = $this->getTimeTableFromDB($short_name);
            return View::make('admin/reports/showtimetable')->with('teacher_name', $short_name)->with('results', $timetables);
        }
        //else if the teacher does not have any timetable
        return View::make('admin/actions/createtimetable')->with('teacher_name', $short_name);

	}

    /**
     * Query and return timetable(s)
     *
     * @param null $short_name
     * @return mixed
     */
    private function getTimeTableFromDB($short_name = null)
    {
        if ($short_name) {
            return Timetable::where('short_name','=',$short_name)->get();
        }
        else //return the teachers without any timetable
        return Teacher::whereNotIn('short_name', function ($query) {
            $query->select('short_name')->from('timetable');
        })->get();
//        return Timetable::all();
    }

    /**
     * Read and process raw timetables from the excel sheet uploaded
     *
     * @return array
     */
    private function getTimeTableFromFile()
    {
        //temporary array whcih stores timetable with all 5 days in a week for each teach in a row
        $rawtimetables = array();

        //final result array which stores timetable separately for each day and each teacher in a row
        $timetables = array();

        //extract timetable from uploaded file
        $rows = Excel::selectSheets('Teacher')->load(public_path().'/uploads/timetables', function($reader) {
            $reader->ignoreEmpty();
            $reader->noheading();

            //skipping headers
            $reader->skip(6);
        })->all();

        //Get two lines for each teachers, and merge the two lines
        for ($counter = 0; $counter < sizeof($rows)-1; $counter+=4) {
            if ($rows[$counter] && $rows[$counter + 1]) {
                $timetable = $rows[$counter];
                foreach ($rows[$counter + 1] as $key => $venue) {
                    $timetable->put($key,$timetable->get($key).'@'.$venue);
                }
                array_push($rawtimetables, $timetable);
            }
        }

        //For each teacher, divide the timetable into 5 days
        foreach ($rawtimetables as $timetable) {
            for ($i = 1; $i <= 5; ++$i) {   //for each day from mon to fri
                $dividedtimetable = array(
                    'short_name' => $timetable->get('1'), //name is the same for each record
                    'day' => $i,
                );

                for ($j = 1; $j <= 13; ++$j) {  //traverse from 1st slot to 13th slot
                    $dividedtimetable["slot_" . $j] = $timetable->get(strval(($i - 1) * 13 + $j + 1));
                }
                array_push($timetables, $dividedtimetable);
            }
        }

        return $timetables;
    }

	/**
	 * Store the timetables into the database
     *
	 * @return Response
	 */
	public function store()
	{
        $file = Input::file('timetablefile');
        $destinationPath = 'uploads';

        $filename = 'timetables';
        //$extension =$file->getClientOriginalExtension();
        //$filename = $file->getClientOriginalName();

        $upload_success = Input::file('timetablefile')->move($destinationPath, $filename);


        $timetables = $this->getTimeTableFromFile();

        //purge after processing
        File::delete(public_path().'/uploads/timetables');

        if( $upload_success ) {

            //Insert records
            DB::table('timetable')->delete();
            DB::table('timetable')->insert($timetables);

            return Redirect::to('admin/actions/upload')->withErrors(['type'=>'timetable','success'=>true]);
        } else {
            return Redirect::to('admin/actions/upload')->withErrors(['type'=>'timetable','success'=>false, 'message'=>"Upload Failed"]);;
        }
	}

    /**
     * Process and store the timetable created through the form
     *
     * @param $short_name
     * @return mixed
     */
	public function create($short_name)
	{
        $inputs = Input::except('_token');
        $timetables = array(
            1 => array(),
            2 => array(),
            3 => array(),
            4 => array(),
            5 => array(),
        );

        for($i=1; $i<=5;$i++) {
            $timetables[$i] = array_merge($timetables[$i], array('short_name' => $short_name, 'day'=>$i));
        }

        foreach ($inputs as $key=>$value) {
            $day = explode('-', $key)[0];
            $slot = explode('-', $key)[1];
            $timetables[$day] = array_merge($timetables[$day], array($slot=>$value));
        }

        DB::table('timetable')->insert($timetables);
        return Redirect::to('admin/actions/createtimetable/'.$short_name)->withErrors(['success'=>true]);
	}
//
//	/**
//	 * Show the form for editing the specified resource.
//	 * GET /admin/timetable/{id}/edit
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function edit($id)
//	{
//		return 0;
//	}
//
//	/**
//	 * Update the specified resource in storage.
//	 * PUT /admin/timetable/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function update($id)
//	{
//		return 0;
//	}

}