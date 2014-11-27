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

Route::get('/', [ 'before' => 'auth', function()
{
	return View::make('home');
} ] );

Route::get ('login' , 'LoginController@requestLogin');
Route::post('login' , 'LoginController@doLogin');
Route::post('register' , 'LoginController@doRegister');
Route::get ('logout', 'LoginController@requestLogout');

// Game logic
Route::get('game_menu', 'GameListController@listGames');

// Game mechanics added 'on the flight'
//   Each game mechanics will need:
//   a URL        -- URL to map (Matching URL's provided by GameListController@listGames
//   any VIEW declared by the controller
//   a CONTROLLER -- A controller class / function
Route::get('playGame', 'GameController@playGame');
Route::post('submitGame', 'GameController@submitGame');

//Submit mechanics
Route::post('submitJudgement','SubmitController@submitJudgement');


// Administrator module routes -- maybe even put in another file ?
Route::get ('admin/login' , 'AdminController@requestLogin');
Route::post('admin/login' , 'AdminController@doLogin');
Route::get('admin/logout' , 'AdminController@requestLogout');

Route::get ('admin', [ 'before' => 'adminauth', 'uses' => 'AdminController@home' ] );

Route::get('admin/listuser', [ 'before' => 'adminauth', 'uses' => 'AdminController@listUsersView' ] );
Route::post('admin/listuser', [ 'before' => 'adminauth', 'uses' => 'AdminController@listUsersAction' ] );

Route::get('admin/createuser', [ 'before' => 'adminauth', 'uses' => 'AdminController@newUserView' ] );
Route::post('admin/createuser', [ 'before' => 'adminauth', 'uses' => 'AdminController@newUserAction' ] );

Route::get('admin/listTask', [ 'before' => 'adminauth', 'uses' => 'AdminController@listTaskView' ] );
Route::post('admin/listTask', [ 'before' => 'adminauth', 'uses' => 'AdminController@listTaskAction' ] );

Route::get('admin/createTask', [ 'before' => 'adminauth', 'uses' => 'AdminController@newTaskView' ] );
Route::post('admin/createTask', [ 'before' => 'adminauth', 'uses' => 'AdminController@newTaskAction' ] );

Route::get('admin/listTaskType', [ 'before' => 'adminauth', 'uses' => 'AdminController@listTaskTypeView' ] );
Route::get('admin/listTaskTypeAction', [ 'before' => 'adminauth', 'uses' => 'AdminController@listTaskTypeAction' ] );

Route::get('test', function() {
	return View::make('test');
});
