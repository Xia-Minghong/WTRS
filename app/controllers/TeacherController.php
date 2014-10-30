<?php

class TeacherController extends \BaseController {

	/**
	 * Create a view for the sign-in page
     *
	 * @return Response
	 */
	public function signin()
	{
        return View::make("signin");
	}

    /**
     * Verify the NRIC entered by the teacher and redirect the client according to the result
     *
     * @return mixed
     */
    public function signinVerify()
    {
        if ($teacher = Teacher::where('nric', '=', Input::get('nric'))->first())
        {
            Auth::teacher()->login($teacher);
            return Redirect::intended('/');
        }
        return Redirect::to('/signin')->withErrors(array('message'=>'Invalid NRIC!'));
    }

    /**
     * Sign-out the teacher
     *
     * @return mixed
     */
    public function signout()
    {
        Auth::teacher()->logout();
        return Redirect::to('/');
    }

}