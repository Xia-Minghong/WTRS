<?php

class ParticularController extends \BaseController {


    /**
     * The headers of all the fields included in the excel with their column indexes
     *
     * @var array
     */
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
        'contact_nos'=> 12,
//        'EXCO' => 10,
//        'Remarks' => 11,
//        'Notebook S/No' => 12,
    );

    /**
     * The fields that are actually needed to be stored in the database
     *
     * @var array
     */
    protected static $shrink_rule = array(
        'nric',
        'title',
        'full_name',
        'short_name',
        'designation',
        'grouping',
        'email_address',
        'contact_nos',
    );

    /**
     * Convert a column header name into corresponding index as defined in $header_index
     *
     * @param $field
     * @return mixed
     */
    public static function get_header_index($field)
    {
        return ParticularController::$header_index[$field];
    }

    /**
     * Create the view displaying a list of teachers' particulars
     *
     * @param null $teacherid
     * @return mixed
     */
	public function index()
	{
        $particulars = $this->getParticularFromDB();

        return View::make('admin/reports/showparticular')->with('results',$particulars);
	}

    /**
     * A function to extract teachers' particulars records from the uploaded excel sheet
     *
     * @return array
     */
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
            if (!$particular->isEmpty() && $particular->get('3')!='') { //Full name non-empty
                array_push($particulars, $particular);
            }
        }

        return $particulars;
    }

	/**
	 * Validate and store the particulars into the database.
     *
	 * @return Response
	 */
	public function store()
	{
        $file = Input::file('particularfile');
        $destinationPath = public_path().'/uploads';

        $filename = 'particulars';
        $upload_success = Input::file('particularfile')->move($destinationPath, $filename);

        if( $upload_success ) {
            //Srhink the particulars to only some fields
            $particulars = $this->shrink($this->getParticularFromFile(), ParticularController::$shrink_rule);

            //purge the file after processing
            File::delete(public_path().'/uploads/particulars');

            //Check for name duplicates before purge and insert
            $short_names = array();
            foreach ($particulars as &$particular) { //enable modification
                if (!$particular['short_name']) {
                    $particular['short_name'] = $particular['full_name'];
                    //fallback using full name as the short_name (primary key) in case of an empty short name

//                    @Depreciated error message
//                    return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>false, 'message'=>"Empty short names found"]);
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

            return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>true]);
        } else {
            return Redirect::to('admin/actions/upload')->withErrors(['type'=>'particular','success'=>false, 'message'=>"Upload Failed"]);
        }
	}

    /**
     * A function to shrink the full sheet from the file into the format that
     * is ready to be stored into the database, with only the columns defined
     * in $shrink_rule
     *
     * @param $particulars
     * @param $shrink_rule
     * @return array
     */
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

    /**
     * Query and return the teachers' particulars from the database
     *
     * @return mixed
     */
    private function getParticularFromDB()
    {
        return $teachers = Teacher::all();
    }

    /**
     * A function to create a MC score record for newly added teachers
     */
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