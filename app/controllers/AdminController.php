<?php

class AdminController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /admin
	 *
	 * @return Response
	 */
    public function index()
    {
        return Redirect::to('admin/actions/generaterelief');
    }

	public function signin()
	{
        return View::make("admin/signin");
	}

    public function signinVerify()
    {
        if (Auth::admin()->attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
        {
            return Redirect::intended('admin');
        }
//        return Hash::make(Input::get('password'));
        return Redirect::to('admin/signin')->withErrors(array('message'=>'Wrong Credentials!'));
    }

    public function signout()
    {
        Auth::admin()->logout();
        return Redirect::to('/');
    }

    public function changepwd()
    {
        return View::make('admin/actions/changepwd');
    }

    public function storepwd()
    {
        $admin = Auth::admin()->get();
        $admin->password = Hash::make(Input::get('newpassword'));
        $admin->save();
        return Redirect::to('admin/actions/changepwd')->withErrors(array('success'=>true));
    }



    /**
     * Show the form for uploading a new resource.
     * GET /admin/timetable/uploade
     *
     * @return Response
     */
    public function upload()
    {
        return View::make('admin/actions/upload');
    }


//	/**
//	 * Show the form for creating a new resource.
//	 * GET /admin/create
//	 *
//	 * @return Response
//	 */
//	public function create()
//	{
//		//
//	}
//
//	/**
//	 * Store a newly created resource in storage.
//	 * POST /admin
//	 *
//	 * @return Response
//	 */
//	public function store()
//	{
//		//
//	}
//
//	/**
//	 * Display the specified resource.
//	 * GET /admin/{id}
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
//	 * GET /admin/{id}/edit
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
//	 * PUT /admin/{id}
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
//	 * DELETE /admin/{id}
//	 *
//	 * @param  int  $id
//	 * @return Response
//	 */
//	public function destroy($id)
//	{
//		//
//	}

}