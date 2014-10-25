<?php

class TeacherController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /signin
	 *
	 * @return Response
	 */
	public function signin()
	{
        return View::make("signin");
	}

    public function signinVerify()
    {
        if ($teacher = Teacher::where('nric', '=', Input::get('nric'))->first())
        {
            Auth::teacher()->login($teacher);
            return Redirect::intended('/');
        }
        return Redirect::to('/signin')->withErrors(array('message'=>'Invalid NRIC!'));
    }

    public function signout()
    {
        Auth::teacher()->logout();
        return Redirect::to('/');
    }

}