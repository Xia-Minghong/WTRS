<?php

class ParticularController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /particular
	 *
	 * @return Response
	 */

    // The headers of all the fields included in the excel with index
    protected static $header_index = array(
        'S/No' => 1,
        'nric' => 2,
        'title' => 3,
        'full_name' => 4,
        'short_name' => 6,  //4,
        'grouping'=> 7,
        'designation' => 8,
        'department' => 9,
        'email_address'=> 10,
//        'Class' => 10,
//        'contact_nos'=> 11,
//        'EXCO' => 10,
//        'Remarks' => 11,
//        'Notebook S/No' => 12,
    );

    //Only the following fields are stored in the database
    protected static $shrink_rule = array(
        'nric',
        'title',
        'full_name',
        'short_name',
        'designation',
        'grouping',
        'email_address',
//        'contact_nos',
    );

    public static function get_header_index($field)
    {
        return ParticularController::$header_index[$field];
    }

	public function index($teacherid = null)
	{
        $particulars = $this->getParticularFromDB();

        return View::make('admin/reports/showparticular')->with('teacherid',$teacherid)->with('results',$particulars);
	}

    private function getParticularFromFile()
    {
        $particulars = array();
        $rawtables = Excel::selectSheetsByIndex(0)->load(public_path().'/uploads/particulars', function($reader) {
            $reader->ignoreEmpty();
            $reader->noheading();
            $reader->skip(1);
        })->all();

        //remove empty lines
        foreach ($rawtables as $particular) {
            if (!$particular->isEmpty() && $particular->get('2')!='') { //NRIC non-empty
                array_push($particulars, $particular);
            }
        }

        //Merging classes with grades
        return $particulars;
    }

	/**
	 * Store a newly created resource in storage.
	 * POST /particular
	 *
	 * @return Response
	 */
	public function store()
	{
        $file = Input::file('particularfile');
        $destinationPath = 'uploads';

        $filename = 'particulars';
        $upload_success = Input::file('particularfile')->move($destinationPath, $filename);


        //Srhink the particulars to only some fields
        $particulars = $this->shrink($this->getParticularFromFile(), ParticularController::$shrink_rule);

        //purge the file after processing
        File::delete(public_path().'/uploads/particulars');

        if( $upload_success ) {
            //Check for name duplicates before purge and insert
            $short_names = array();
            foreach ($particulars as $particular) {
                if (!$particular['short_name']) {
                    return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>false, 'message'=>"Empty short names found"]);
                }
                array_push($short_names, $particular['short_name']);
                //If duplicates found, error
                if (count($short_names) !== count(array_unique($short_names))) {
                    return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>false, 'message'=>"Duplicated short names found"]);
                }
            }


            //Purge the original table and insert the new records
            DB::table('teacher')->delete();
            DB::table('teacher')->insert($particulars);

            //Perform referential check on MC scores
            $this->updateMcScore();
//            return $particulars;
            return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>true]);
        } else {
            return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>false, 'message'=>"Upload Failed"]);
        }
	}

    public function shrink($particulars, $shrink_rule)
    {
        $shrinked_particulars = array();
        foreach ($particulars as $particular) {
            $shrinked_particular = array();
            for ($i = 0; $i < sizeof($shrink_rule); $i++){
                $index = ParticularController::get_header_index($shrink_rule[$i]);
                $item = $particular->get(strval($index));

                //Get the number in the grouping field, ignore all text such as "Grp"
                if ($shrink_rule[$i] == 'grouping') {
                    $item = preg_replace("/[^0-9]/", '', $item);
                    if ($item=="") $item = "2";
                    $item = intval($item);
                }

                $shrinked_particular[$shrink_rule[$i]]=$item;
            }
            array_push($shrinked_particulars, $shrinked_particular);
        }
        return $shrinked_particulars;
    }

    private function getParticularFromDB()
    {
        return $teachers = Teacher::all();
    }

    private function updateMcScore()
    {
        //add MC scores of 0 for new teachers
        if ($teachers_noMcScore = Teacher::leftjoin('mc_score', 'teacher.short_name', '=', 'mc_score.short_name')
            ->whereNull('mc_score.mc_score')
            ->select('teacher.short_name')
            ->get()
        ) {
            foreach ($teachers_noMcScore as $teacher) {
                DB::table('mc_score')->insert(
                    array(
                        'short_name' => $teacher['short_name'],
                        'mc_score' => 0,
                    )
                );
            }
        }

        //delete MC score records for deleted teachers
        DB::table('mc_score')->leftjoin('teacher', 'teacher.short_name', '=', 'mc_score.short_name')
            ->whereNull('teacher.short_name')->delete();
    }








//
//	/**
//	 * Display the specified resource.
//	 * GET /particular/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function show($id)
//	{
//		//
//	}
//
//	/**
//	 * Show the form for editing the specified resource.
//	 * GET /particular/{id}/edit
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function edit($id)
//	{
//		//
//	}
//
//	/**
//	 * Update the specified resource in storage.
//	 * PUT /particular/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function update($id)
//	{
//		//
//	}
//
//	/**
//	 * Remove the specified resource from storage.
//	 * DELETE /particular/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function destroy($id)
//	{
//		//
//	}

}