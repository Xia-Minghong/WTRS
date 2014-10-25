<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Authentication checked inside the controller
//since both the admin and the teachers can use this route
Route::post('/', 'MCController@store');

// Protected Teacher Routes
Route::group(array('before' => 'auth'), function() {
    Route::get('/', 'HomeController@submitMC');
    Route::get('signout', 'TeacherController@signout');
});

Route::get('signin', 'TeacherController@signin');
Route::post('signin', 'TeacherController@signinVerify');





// Route::get('admin', array('before'=>'auth.admin', 'uses'=>'AdminController@index'));

Route::get('admin/signin', 'AdminController@signin');
Route::post('admin/signin', 'AdminController@signinVerify');

// Protected Administrative Routes
Route::group(array('before' => 'auth.admin'), function()
{
    Route::get('admin', 'AdminController@index');

    Route::get('admin/reports/timetable/{short_name?}', 'TimeTableController@index');
    Route::get('admin/reports/particular/{short_name?}', 'ParticularController@index');

    Route::get('admin/reports/MClist', 'MCController@MClist');
    Route::post('admin/reports/MClist/delete', 'MCController@delete');

    Route::get('admin/reports/MCscore', 'MCController@MCscore');
    Route::post('admin/reports/MCscore/edit', 'MCController@editScore');


    Route::get('admin/actions/createMC', 'MCController@create');
    Route::get('admin/actions/createtimetable/{short_name?}', 'TimeTableController@index');
    Route::post('admin/actions/createtimetable/{short_name}', 'TimeTableController@create');

    Route::get('admin/actions/generaterelief', 'ReliefController@index');
    Route::post('admin/actions/generaterelief', 'ReliefController@store');
    Route::get('admin/actions/generaterelief/confirm', 'ReliefController@confirm');

    Route::get('admin/actions/changepwd', 'AdminController@changepwd');
    Route::post('admin/actions/changepwd', 'AdminController@storepwd');

    Route::get('admin/actions/upload', 'AdminController@upload');
    Route::post('admin/actions/upload/timetable', 'TimeTableController@store');
    Route::post('admin/actions/upload/particular', 'ParticularController@store');

    Route::get('admin/signout', 'AdminController@signout');
});
