<?php

class AdminController extends \BaseController {

    /**
     * Redirect the URL visit of "/admin" to "/admin/actions/generaterelief"
     *
     * @return mixed
     */
    public function index()
    {
        return Redirect::to('admin/actions/generaterelief');
    }

    /**
     * Create and return the admin signin view
     *
     * @return mixed
     */
	public function signin()
	{
        return View::make("admin/signin");
	}

    /**
     * Verify the admin account from the sign in page and redirect according to the result of authentication
     *
     * @return mixed
     */
    public function signinVerify()
    {
        if (Auth::admin()->attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
        {
            return Redirect::intended('admin');
        }
//        return Hash::make(Input::get('password'));
        return Redirect::to('admin/signin')->withErrors(array('message'=>'Wrong Credentials!'));
    }

    /**
     * Sign out the admin
     *
     * @return mixed
     */
    public function signout()
    {
        Auth::admin()->logout();
        return Redirect::to('/');
    }

    /**
     * Create the view for changing admin password
     *
     * @return mixed
     */
    public function changepwd()
    {
        return View::make('admin/actions/changepwd');
    }

    /**
     * Store the new admin password
     *
     * @return mixed
     */
    public function storepwd()
    {
        $admin = Auth::admin()->get();
        $admin->password = Hash::make(Input::get('newpassword'));
        $admin->save();
        return Redirect::to('admin/actions/changepwd')->withErrors(array('success'=>true));
    }


    /**
     * Create the view for the system input upload page
     *
     * @return mixed
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