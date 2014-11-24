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

Route::get('/', array('before' => 'auth', function()
{
	return View::make('home');
}));

Route::get ('login' , 'LoginController@requestLogin');
Route::post('login' , 'LoginController@doLogin');
Route::post('register' , 'LoginController@doRegister');
Route::get ('logout', 'LoginController@requestLogout');

// Task logic
Route::get('Task_menu', 'TaskListController@listTasks');

// Task mechanics added 'on the flight'
//   Each Task mechanics will need:
//   a URL        -- URL to map (Matching URL's provided by TaskListController@listTasks
//   any VIEW declared by the controller
//   a CONTROLLER -- A controller class / function
Route::get('playTask', 'TaskController@playTask');
Route::post('submitTask', 'TaskController@submitTask');


// Administrator module routes -- maybe even put in another file ?
Route::get ('adminlogin' , 'AdminController@requestLogin');
Route::post('adminlogin' , 'AdminController@doLogin');
Route::get('adminlogout' , 'AdminController@requestLogout');

Route::get ('admin', array('before' => 'adminauth', 'uses' => 'AdminController@home'));
// Route::get ('admin', 'AdminController@home');

Route::get('adminlistuser', array('before' => 'adminauth', 'uses' => 'AdminController@listUsersView'));
Route::post('adminlistuser', array('before' => 'adminauth', 'uses' => 'AdminController@listUsersAction'));

Route::get('admincreateuser', array('before' => 'adminauth', 'uses' => 'AdminController@newUserView'));
Route::post('admincreateuser', array('before' => 'adminauth', 'uses' => 'AdminController@newUserAction'));

Route::get('adminlistTask', array('before' => 'adminauth', 'uses' => 'AdminController@listTaskView'));
Route::post('adminlistTask', array('before' => 'adminauth', 'uses' => 'AdminController@listTaskAction'));

Route::get('admincreateTask', array('before' => 'adminauth', 'uses' => 'AdminController@newTaskView'));
Route::post('admincreateTask', array('before' => 'adminauth', 'uses' => 'AdminController@newTaskAction'));



Route::get('test', function() {
	return View::make('test');
});
