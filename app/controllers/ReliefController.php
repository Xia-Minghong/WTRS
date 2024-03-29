<?php

class ReliefController extends \BaseController {

    /**
     * hold the number of times a group 1 teacher is on relief temporarily
     * @var array
     */
    static $grp1_relief_counter = [];

    /**
     * hold the number of times a group 2 teacher is on relief temporarily
     * @var array
     */
    static $grp2_relief_counter = [];

    /**
     * Generate the relief arrangements and display.
     * @return mixed
     */
	public function index()
	{
        //Delete relief records from the previous day
        $this->deletePrev();

        $date = new DateTime('today');

        //If now is weekends, set displayed date to next Monday
        if (date("w") == 0) {
            $date->add(new DateInterval('P1D')); //plus 1 day
        } elseif(date("w") == 6) {
            $date->add(new DateInterval('P2D')); //plus 2 days
        }


        //generate reliefs (ignore relieved ones)
        $relief_timetables = DB::table('relief_timetable')
            ->join('absence','relief_timetable.mc_id','=','absence.mc_id')
            ->where('date','=', $date)
            ->join('teacher', 'teacher.short_name', '=', 'absence.short_name')  //join with the teacher information table
            ->select(array('record_id', 'relief_timetable.mc_id', 'teacher.short_name', 'date', 'full_name', 'slot', 'relief_short_name', 'confirmed'))
            ->get();

        //loop through all slots that needs to be relieved
        foreach($relief_timetables as $relief_timetable) {
            //get the timetable for the teacher on leave
            $timetable = Timetable::where('short_name', '=', $relief_timetable->{'short_name'})
                ->where('day', '=', idate("w", strtotime($date->format("d-m-Y"))))->first();

            //add in the corresponding class name, time and venue into the relief timetable
            $relief_timetable->{'slot_value'} = $timetable['slot_' . $relief_timetable->{'slot'}];
            $relief_timetable->{'slot_time'} = $this->slotToTime($relief_timetable->{'slot'});


//            if (!$relief_timetable->{'relief_short_name'}) {
//                $teacher = $relief_timetable->{'relief_short_name'};
//                if(!array_key_exists($teacher, self::$relief_counter)) {
//                    self::$relief_counter[$teacher]=0;
//                }
//                self::$relief_counter[$teacher]+=1;
//            }

            //setting default relief teachers
            $relief_timetable->{'relief_teacher'} = $this->getReliefTeacher($date, $timetable['day'], $relief_timetable->{'slot'});
        }

//        return self::$relief_counter;
//        return $relief_timetables;
        return View::make('admin/actions/generaterelief')->with('results',$relief_timetables);
	}

	/**
	 * Store the submitted relief arrangement
	 */
	public function store()
	{
        $reliefs = Input::except(array("_token", "final"));
        foreach ($reliefs as $record_id => $relief) {
            $record = ReliefTimetable::find($record_id);
            $record->relief_short_name = $relief;
            $record->save();
        }

        //if final submission
        if(Input::get('final')=="yes") {
            //Adding 1 mc score for each teacher
            $relief_teachers = array();
            foreach ($reliefs as $record_id => $relief) {
                if($relief) {
                    array_push($relief_teachers, $relief);
                }
            }
            $relief_teachers = array_unique($relief_teachers);

            foreach ($relief_teachers as $shortname) {
                $teacher = MCScore::find($shortname);
                $teacher->mc_score += 1;
                $teacher->save();
            }
            return Redirect::to('admin/actions/generaterelief')->withErrors(['success'=>true, 'final'=>true]);
        }

        return Redirect::to('admin/actions/generaterelief')->withErrors(['success'=>true]);
	}


    /**
     * Confirm the relief arrangement, marking all records as "confirmed"
     *
     * @return mixed
     */
	public function confirm()
	{
        $date = new DateTime('today');
        if (date("w") == 0) {
            $date->add(new DateInterval('P1D'));
        } elseif(date("w") == 6) {
            $date->add(new DateInterval('P2D'));
        }
        $day = idate("w", strtotime($date->format("d-m-Y")));

        //Mark all reliefs as comfirmed if the submission is confirmed
        DB::table('relief_timetable')->update(array('confirmed' => 1));

//      @depreciated
//      Delete all records after confirmation
//        DB::table('relief_timetable')
//            ->join('absence', 'relief_timetable.mc_id', '=', 'absence.mc_id')
//            ->where('date', '=', $date)
//            ->delete();

        //get data for the relief message sheet
        $relief_timetables = DB::table('relief_timetable')
            ->join('teacher as relief_teacher', 'relief_timetable.relief_short_name', '=', 'relief_teacher.short_name')
            ->join('absence','relief_timetable.mc_id','=','absence.mc_id')
            ->join('teacher as on_leave_teacher', 'on_leave_teacher.short_name', '=', 'absence.short_name')
            ->join('timetable', 'timetable.short_name', '=', 'on_leave_teacher.short_name')
            ->where('timetable.day', '=', $day)
            ->where('absence.date', '=', $date)
            ->orderBy('relief_timetable.relief_short_name')
            ->select(
                'relief_teacher.title as relief_teacher_title',
                'relief_teacher.full_name as relief_teacher_full_name',
                'on_leave_teacher.title as on_leave_teacher_title',
                'on_leave_teacher.full_name as on_leave_teacher_full_name',
                'timetable.*',
                'relief_timetable.slot'
            )->get();
        //create the relief message sheet
        return View::make('admin/reports/reliefmessages')->with('results', $relief_timetables);
	}

	/**
	 * Delete relief records from previous days
     *
	 * @return Response
	 */
	public function deletePrev()
	{
		DB::table('relief_timetable')
            ->join('absence', 'relief_timetable.mc_id', '=', 'absence.mc_id')
            ->where('date', '<', new DateTime('today'))
            ->delete();
	}

    /**
     * convert the slot ID (1-13) in to corresponding time slot strings
     *
     * @param $slot
     * @return mixed
     */
    private function slotToTime($slot)
    {
        $slot_str = array();
        for($i=0;$i<=21600;$i+=1800){
            if ($i<=18000) {
                //07:30-08:30 ... 12:30-13:30
                array_push($slot_str, date('H:i', mktime(7, 30, 0, 1, 1) + $i).'-'.date('H:i', mktime(7, 30, 0, 1, 1) + $i + 1800));
            } else {
                //special slots calculation of 13:00-13:25 and 13:25-13:50
                array_push($slot_str, date('H:i', mktime(7, 30, 0, 1, 1) + $i).'-'.date('H:i', mktime(7, 30, 0, 1, 1) + $i + 1500));
                $i-=300;
            }
        }
        return $slot_str[$slot-1];
    }

    /**
     * A function to compare group 1 teachers according to the grp1_relief_counter
     * @param $a
     * @param $b
     * @return int
     */
    private function cmpTeacher($a, $b)
    {
        if (self::$grp1_relief_counter[$a['short_name']] == self::$grp1_relief_counter[$b['short_name']]) {
            return 0;
        }
        return (self::$grp1_relief_counter[$a['short_name']] < self::$grp1_relief_counter[$b['short_name']]) ? -1:1;
    }

    /**
     * Return a list of teachers who are available to relief at the given slot
     *
     * @param $date
     * @param $day
     * @param $slot
     * @return array
     */
    private function getReliefTeacher($date, $day, $slot)
    {
        //Select group 1 teachers first
        $grp1_teachers = Teacher::whereNotIn('teacher.short_name', Absence::where('date','=', $date)->lists('short_name'))
            ->where('grouping','=',1)
            ->whereIn('teacher.short_name',
                Timetable::where('day', '=', $day)
                    ->where('slot_'.$slot, '=', '')
                    ->lists('short_name')
            )
            ->select('teacher.short_name', 'teacher.full_name', 'grouping')
            ->get()->toArray();

        foreach ($grp1_teachers as $key=>$grp1_teacher) {
            //Add all group 1 teachers in to grp1_relief_counter
            if(!array_key_exists($grp1_teacher['short_name'], self::$grp1_relief_counter)) {
                self::$grp1_relief_counter[$grp1_teacher['short_name']] = 0;
            }
        }

        //Sort the grp1_teachers in decreasing order according to the relief counter
        uasort($grp1_teachers, 'self::cmpTeacher');

        //fix the array index
        $grp1_teachers = array_values($grp1_teachers);

        //Select group 2 teachers
        $grp2_teachers = DB::table('teacher')
            ->whereNotIn('teacher.short_name', Absence::where('date','=',$date)->lists('short_name'))
            ->where('teacher.grouping','=',2)
            ->whereIn('teacher.short_name',
                Timetable::where('day', '=', $day)
                    ->where('slot_'.$slot, '=', '')
                    ->lists('short_name')
            )
            ->join('mc_score', 'teacher.short_name', '=', 'mc_score.short_name')
            ->orderBy('mc_score')
            ->select('teacher.short_name', 'teacher.full_name', 'grouping', 'mc_score')
            ->get();

        //Map the group 2 query result (json object) to array
        $grp2_teachers = array_map(function($val)
        {
            return json_decode(json_encode($val), true);
        }, $grp2_teachers);

        $candidates = array_merge($grp1_teachers, $grp2_teachers);

        //short-listing the relief teachers for the slot
        $priority = 0; //the position of a teacher in the dropdown list, priority=1 means the teacher is the default value
        $head_key = 0; //keep track of the key value of the first item in the candidate array
        foreach ($candidates as $key=>$teacher) {
            $priority+=1;

            //Use group1 teachers first until each of them has only 2 vacant slots for the day
            //Then use group2 teacher, keep the number of reliefs for each group2 teacher below 2
            //Make sure each teacher has at least 2 vacant slots
            if (array_key_exists($teacher['short_name'], self::$grp2_relief_counter)
                && (self::$grp2_relief_counter[$teacher['short_name']]>=2
                || $this->countVacantSlot($teacher['short_name'], $day) - self::$grp2_relief_counter[$teacher['short_name']] <= 2)
            ) {
                unset($candidates[$key]);
                //if the removed is the first teacher, the relief counter for the next teacher should be increased by 1
                //as it automatically become the default value
                if ($key == $head_key) {
                    $priority -= 1;
                    $head_key += 1;
                }
            } else {
                if(!array_key_exists($teacher['short_name'], self::$grp2_relief_counter)) {
                    self::$grp2_relief_counter[$teacher['short_name']]=0;
                }

                //+1 in the grp1_relief_counter for the default teacher chosen to be displayed
                if($priority == 1 && $teacher['grouping']==1) {
                    self::$grp1_relief_counter[$teacher['short_name']]+=1;
                }

                //+1 in the grp2_relief_counter for the default teacher chosen to be displayed
                if($priority == 1 && $teacher['grouping']==2) {
                    self::$grp2_relief_counter[$teacher['short_name']]+=1;
                }
            }
        }

        //Adjust Array Index of thr previous results
        $teachers = array();
        foreach ($candidates as $teacher) {
            array_push($teachers, $teacher);
        }
        return $teachers;
    }

    /**
     * Given the name of the teacher and day of week, calculate the number of vacant slots
     *
     * @param $short_name
     * @param $day
     * @return int
     */
    private function countVacantSlot($short_name, $day)
    {
        $count = 0;
        $timetable = Timetable::where('short_name','=',$short_name)->where('day', '=', $day)->first()->toArray();
        for ($i = 1; $i <= 13; ++$i) {
            if (str_replace(' ', '', $timetable['slot_' . $i]) == "") {
                $count+=1;
            }
        }
        return $count;
    }

}